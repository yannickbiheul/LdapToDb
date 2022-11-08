<?php

namespace App\Controller;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ServiceController extends AbstractController
{
    /**
     * Afficher la liste des services
     */
    #[Route('/api/services', name: 'services', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository, SerializerInterface $serializer): JsonResponse
    {
        $listServices = $serviceRepository->findAll();
        $jsonListServices = $serializer->serialize($listServices, 'json', ['groups' => 'getServices']);

        return new JsonResponse($jsonListServices, Response::HTTP_OK, [], true);
    }

    /**
     * Afficher un service
     */
    #[Route('/api/services/{id}', name: 'detailService', methods: ['GET'])]
    public function getDetailService(Service $service, SerializerInterface $serializer): JsonResponse
    {
        $jsonService = $serializer->serialize($service, 'json', ['groups' => 'getServices']);
        return new JsonResponse($jsonService, Response::HTTP_OK, [], true);
    }
}
