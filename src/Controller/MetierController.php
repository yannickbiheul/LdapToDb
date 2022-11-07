<?php

namespace App\Controller;

use App\Entity\Metier;
use App\Repository\MetierRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MetierController extends AbstractController
{
    /**
     * Afficher la liste des métiers
     */
    #[Route('/api/metiers', name: 'metiers')]
    public function index(MetierRepository $metierRepository, SerializerInterface $serializer): JsonResponse
    {
        $listMetiers = $metierRepository->findAll();
        $jsonListMetiers = $serializer->serialize($listMetiers, 'json', ['groups' => 'getMetiers']);

        return new JsonResponse($jsonListMetiers, Response::HTTP_OK, [], true);
    }

    /**
     * Afficher un métier
     */
    #[Route('/api/metiers/{id}', name: 'detailMetier', methods: ['GET'])]
    public function getDetailMetier(Metier $metier, SerializerInterface $serializer): JsonResponse
    {
        $jsonMetier = $serializer->serialize($metier, 'json', ['groups' => 'getMetiers']);
        return new JsonResponse($jsonMetier, Response::HTTP_OK, [], true);
    }
}
