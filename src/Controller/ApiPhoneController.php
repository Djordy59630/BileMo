<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Service\ApiGetService;
use App\Repository\PhoneRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/phone')]
class ApiPhoneController extends AbstractController
{

    #[Route('/', name: 'app_api_phone_index', methods: ['GET'])]
    public function index(PhoneRepository $phoneRepository, SerializerInterface $serializer): JsonResponse 
    {
        $phones = $phoneRepository->FindAll();
        $jsonPhones = $serializer->serialize($phones, 'json');
        return new JsonResponse($jsonPhones, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'app_api_phone_show', methods: ['GET'])]
    public function show(Phone $phone, SerializerInterface $serializer): JsonResponse 
    {
        $jsonPhone = $serializer->serialize($phone, 'json');
        return new JsonResponse($jsonPhone, Response::HTTP_OK, ['accept' => 'json'], true);
    }
}
