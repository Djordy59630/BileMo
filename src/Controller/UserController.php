<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Form\UserType;
use App\Service\ApiGetService;
use App\Repository\UserRepository;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\security as SecurityRoute;

#[Route('api/user')]
class UserController extends AbstractController
{
  
    private Security $security;
    
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, SerializerInterface $serializer,TagAwareCacheInterface $cachePool): Response
    {
       
            // On récupère l'utilisateur courant
            $user = $this->getUser();
            // on regarde su l'utilisateur est super admin
            $isSuperAdmin = $this->security->isGranted('ROLE_SUPER_ADMIN');
            // on rècupere le customer id de l'utilisateur
            $customerId = $user->getCustomer()->getId();
            // on crée une variable de cache unique
            $idCache = "getAllUsers-" . $customerId . implode("-" , $user->getRoles());
            // on utilise cachepool pour faire la mise en cache 
            $users = $cachePool->get($idCache, function (ItemInterface $item) use ($userRepository, $isSuperAdmin, $customerId) {
                $item->tag("UsersCache");
                // Dans le requete apiFindAll on passe en paramètre l'id du customer, et si l'utilisateur est super admin pour faire les vérifications
                // si l'utilisateur courant est super admin alors on envoie l'ensemble des users
                // si l'utilisateur n'est pas super admin alors on lui envoie uniquement les users possédant le même customer id que lui
                return $userRepository->apiFindAll($isSuperAdmin, $customerId);
            });
            // on sérialise les données en Json
            $jsonUsers = $serializer->serialize($users, 'json');
            // on return les données
            return new JsonResponse($jsonUsers, Response::HTTP_CREATED, [], true);
       
    }

    // On sécurise la route new uniquement pour les admin ou super admin
    #[SecurityRoute("is_granted(ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')")]
    #[Route('/', name: 'app_user_new', methods: ['POST'])]
    public function new(Request $request, UserRepository $userRepository,TagAwareCacheInterface $cachePool, UrlGeneratorInterface $urlGenerator , SerializerInterface $serializer, CustomerRepository $customerRepository,UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em): Response
    {

        try
        {
        // On récupère l'utilisateur courant
        $curentUser =  $this->getUser();
        // on regarde su l'utilisateur est super admin
        $isSuperAdmin = $this->security->isGranted('ROLE_SUPER_ADMIN');
        // on rècupere le customer id de l'utilisateur
        $customerId = $curentUser->getCustomer()->getId();
        // on déserialise les données 
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        // Récupération de l'ensemble des données envoyées sous forme de tableau
        $content = $request->toArray();
        // on utilise un voters pour effectuer des vérifications de sécurité
        // si l'utilisateur courant et super admin on lui autorise la création
        // si l'utilisateur est admin on lui vérifie que l'id du customer qu'il est en train de créé correspond au sien
        $this->denyAccessUnlessGranted('USER_CREATE', $content);
        // on récupere l'id du customer du nouvel utilisateur
        if ($isSuperAdmin){
            $idCustomer = $content['customer'] ?? -1;
        }else{
            $idCustomer = $curentUser->getCustomer();
        }
        // on donne au nouvel utilisateur le customer précédemment récupéré 
        $user->setCustomer($customerRepository->find($idCustomer));
        // on encode le password password
         $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $content['password']
            )
        );
        // on ajoute le nouvel utilisateur a la base de données
        $em->persist($user);
        $em->flush();

        // on récupére le nouvel utilisateur
        $user = $userRepository->apiFindOne($isSuperAdmin, $customerId, $user->getId());
        // on sérialise en json
        $jsonUser = $serializer->serialize($user, 'json');
        // on crée un lien pour accéder au show du nouvel utilisateur
        $location = $urlGenerator->generate('app_user_show', ['id' => $user[0]["id"]], UrlGeneratorInterface::ABSOLUTE_URL);

        // Remove all cache keys tagged with "bar"
        $cachePool->invalidateTags(['UsersCache']);

        // on return en ajotant le lien du show dans le header de la reponse
        return new JsonResponse($jsonUser, Response::HTTP_CREATED, ["Location" => $location], true);
        }
        catch( UniqueConstraintViolationException $e)
        {
            return new JsonResponse($e->getMessage(), Response::HTTP_CONFLICT, [], true);
        }
        catch(Exception $e)
        {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, [], true);
        }

    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user, SerializerInterface $serializer, UserRepository $userRepository,TagAwareCacheInterface $cachePool): Response
    {
        // On récupère l'utilisateur courant
        $curentUser = $this->getUser();
        // on regarde su l'utilisateur est super admin
        $isSuperAdmin = $this->security->isGranted('ROLE_SUPER_ADMIN');
        // on rècupere le customer id de l'utilisateur
        $customerId = $curentUser->getCustomer()->getId();
        // on utilise un voters pour effectuer des vérifications de sécurité
        // si l'utilisateur courant et super admin on l'autorise de voir tous les utilisateurs
        // si l'utilisateur est admin on lui vérifie que l'id du customer de l'utilisateur qu'il veut afficher correspond au sien
        $this->denyAccessUnlessGranted('USER_VIEW', $user);
        // on crée une variable de cache unique
         $idCache = "getUser" . $customerId . implode("-" , $user->getRoles());
         // on utilise cachepool pour faire la mise en cache 
         $user = $cachePool->get($idCache, function (ItemInterface $item) use ($userRepository, $isSuperAdmin, $customerId, $user) {
             $item->tag("UserCache");
             // Dans le requete apiFindAll on passe en paramètre l'id du customer, et si l'utilisateur est super admin pour faire les vérifications
             // si l'utilisateur courant est super admin alors on envoie l'user
             // si l'utilisateur n'est pas super admin alors on lui envoie uniquement les users possédant le même customer id que lui
             return $userRepository->apiFindOne($isSuperAdmin, $customerId, $user->getId());
         });
        // on sérialise
        $jsonUser = $serializer->serialize($user, 'json');
        // on return json
        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }


    #[Route('/{id}', name: 'app_user_delete', methods: ['DELETE'])]
    public function delete(Request $request, User $user, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        try
        {
            // On récupère l'utilisateur courant
            $curentUser = $this->getUser();
            // on regarde su l'utilisateur est super admin
            $isSuperAdmin = $this->security->isGranted('ROLE_SUPER_ADMIN');
            // on rècupere le customer id de l'utilisateur
            $customerId = $curentUser->getCustomer()->getId();
            // on utilise un voters pour effectuer des vérifications de sécurité
            // si l'utilisateur courant et super admin on l'autorise de supprimer tous les utilisateurs
            // si l'utilisateur est admin on lui vérifie que l'id du customer de l'utilisateur qu'il veut supprimer correspond au sien
            $this->denyAccessUnlessGranted('USER_DELETE', $user);
            // on supprime l'utilisateur de la base de données 
            $em->remove($user);
            $em->flush();
            // on return HTTP_NO_CONTENT
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        catch(Exception $e)
        {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, [], true);
        }
    }
}
