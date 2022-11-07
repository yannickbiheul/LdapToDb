<?php

namespace App\Controller;

use App\Repository\MetierRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MetierController extends AbstractController
{
    #[Route('/api/metiers', name: 'metiers')]
    public function index(MetierRepository $metierRepository, SerializerInterface $serializer): JsonResponse
    {
        $listMetiers = $metierRepository->findAll();
        $jsonListMetiers = $serializer->serialize($listMetiers, 'json', ['groups' => 'getMetiers']);

        return new JsonResponse($jsonListMetiers, Response::HTTP_OK, [], true);
    }
}
