<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Service\ApiGetService;
use App\Repository\PhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/phone')]
class ApiPhoneController extends AbstractController
{
    #[Route('/', name: 'app_api_phone_index', methods: ['GET'])]
    public function index(PhoneRepository $phoneRepository, ApiGetService $apiGetService): Response
    {
        $phones = $phoneRepository->apiFindAll();
        return $apiGetService->getData($phones);

    }

    #[Route('/{id}', name: 'app_api_phone_show', methods: ['GET'])]
    public function show(Phone $phone, PhoneRepository $phoneRepository, ApiGetService $apiGetService): Response
    {
        $phone = $phoneRepository->apiFindOneBy($phone);
        return $apiGetService->getData($phone);
    }
}
