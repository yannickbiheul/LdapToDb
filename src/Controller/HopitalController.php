<?php

namespace App\Controller;

use App\Repository\HopitalRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HopitalController extends AbstractController
{
    #[Route('/api/hopitaux', name: 'hopitaux')]
    public function index(HopitalRepository $hopitalRepository, SerializerInterface $serializer): JsonResponse
    {
        $listHopitaux = $hopitalRepository->findAll();
        $jsonListHopitaux = $serializer->serialize($listHopitaux, 'json', ['groups' => 'getHopitaux']);

        return new JsonResponse($jsonListHopitaux, Response::HTTP_OK, [], true);
    }
}
