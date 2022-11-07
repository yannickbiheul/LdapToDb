<?php

namespace App\Controller;

use App\Repository\PoleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PoleController extends AbstractController
{
    #[Route('/api/poles', name: 'poles')]
    public function index(PoleRepository $poleRepository, SerializerInterface $serializer): JsonResponse
    {
        $listPoles = $poleRepository->findAll();
        $jsonListPoles = $serializer->serialize($listPoles, 'json', ['groups' => 'getPoles']);

        return new JsonResponse($jsonListPoles, Response::HTTP_OK, [], true);
    }
}
