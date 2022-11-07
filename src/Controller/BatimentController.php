<?php

namespace App\Controller;

use App\Repository\BatimentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BatimentController extends AbstractController
{
    #[Route('/api/batiments', name: 'batiments')]
    public function index(BatimentRepository $batimentRepository, SerializerInterface $serializer): JsonResponse
    {
        $listBatiments = $batimentRepository->findAll();
        $jsonListBatiments = $serializer->serialize($listBatiments, 'json', ['groups' => 'getBatiments']);

        return new JsonResponse($jsonListBatiments, Response::HTTP_OK, [], true);
    }
}
