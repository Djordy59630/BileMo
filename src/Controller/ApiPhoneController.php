<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Service\ApiGetService;
use App\Repository\PhoneRepository;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/phone')]
class ApiPhoneController extends AbstractController
{

    #[Route('/', name: 'app_api_phone_index', methods: ['GET'])]
    public function index(PhoneRepository $phoneRepository, SerializerInterface $serializer,TagAwareCacheInterface $cachePool): JsonResponse 
    {
        $phones = $phoneRepository->FindAll();
      
        // on crée une variable de cache unique
        $idCache = "getPhones";

        // on utilise cachepool pour faire la mise en cache 
        $phones = $cachePool->get($idCache, function (ItemInterface $item) use ($phones) {
            $item->tag("PhonesCache");
            return  $phones;
        });

        // on sérialise
        $jsonPhones = $serializer->serialize($phones, 'json');
        // on return http_ok
        return new JsonResponse($jsonPhones, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'app_api_phone_show', methods: ['GET'])]
    public function show(Phone $phone, SerializerInterface $serializer,TagAwareCacheInterface $cachePool): JsonResponse 
    {
         // on crée une variable de cache unique
         $idCache = "getPhone";
         // on utilise cachepool pour faire la mise en cache 
         $phone = $cachePool->get($idCache, function (ItemInterface $item) use ($phone) {
             $item->tag("PhoneCache");
             return  $phone;
         });
        // on serialise le téléphone
        $jsonPhone = $serializer->serialize($phone, 'json');
        // on
        return new JsonResponse($jsonPhone, Response::HTTP_OK, ['accept' => 'json'], true);
    }
}
