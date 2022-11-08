<?php

namespace App\Controller;

use App\Entity\Pole;
use App\Repository\PoleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PoleController extends AbstractController
{
    /**
     * Afficher la liste des pôles
     */
    #[Route('/api/poles', name: 'poles', methods: ['GET'])]
    public function index(PoleRepository $poleRepository, SerializerInterface $serializer): JsonResponse
    {
        $listPoles = $poleRepository->findAll();
        $jsonListPoles = $serializer->serialize($listPoles, 'json', ['groups' => 'getPoles']);

        return new JsonResponse($jsonListPoles, Response::HTTP_OK, [], true);
    }

    /**
     * Afficher un pôle
     */
    #[Route('/api/poles/{id}', name: 'detailPole', methods: ['GET'])]
    public function getDetailPole(Pole $pole, SerializerInterface $serializer): JsonResponse
    {
        $jsonPole = $serializer->serialize($pole, 'json', ['groups' => 'getPoles']);
        return new JsonResponse($jsonPole, Response::HTTP_OK, [], true);
    }
}
