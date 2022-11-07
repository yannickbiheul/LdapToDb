<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ServiceController extends AbstractController
{
    #[Route('/api/services', name: 'services')]
    public function index(ServiceRepository $serviceRepository, SerializerInterface $serializer): JsonResponse
    {
        $listServices = $serviceRepository->findAll();
        $jsonListServices = $serializer->serialize($listServices, 'json', ['groups' => 'getServices']);

        return new JsonResponse($jsonListServices, Response::HTTP_OK, [], true);
    }
}
