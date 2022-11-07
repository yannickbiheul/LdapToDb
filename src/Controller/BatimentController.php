<?php

namespace App\Controller;

use App\Entity\Batiment;
use App\Repository\BatimentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BatimentController extends AbstractController
{
    /**
     * Afficher la liste des bâtiments
     */
    #[Route('/api/batiments', name: 'batiments')]
    public function index(BatimentRepository $batimentRepository, SerializerInterface $serializer): JsonResponse
    {
        $listBatiments = $batimentRepository->findAll();
        $jsonListBatiments = $serializer->serialize($listBatiments, 'json', ['groups' => 'getBatiments']);

        return new JsonResponse($jsonListBatiments, Response::HTTP_OK, [], true);
    }

    /**
     * Afficher un bâtiment
     */
    #[Route('/api/batiments/{id}', name: 'detailBatiment', methods: ['GET'])]
    public function getDetailBatiment(Batiment $batiment, SerializerInterface $serializer): JsonResponse
    {
        $jsonBatiment = $serializer->serialize($batiment, 'json', ['groups' => 'getBatiments']);
        return new JsonResponse($jsonBatiment, Response::HTTP_OK, [], true);
    }
}
