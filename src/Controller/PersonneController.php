<?php

namespace App\Controller;

use App\Repository\PersonneRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PersonneController extends AbstractController
{
    #[Route('/api/personnes', name: 'personnes', methods: ['GET'])]
    public function index(PersonneRepository $personneRepository, SerializerInterface $serializer): JsonResponse
    {
        $listPersonnes = $personneRepository->findAll();
        $jsonListPersonnes = $serializer->serialize($listPersonnes, 'json', ['groups' => 'getPersonnes']);

        return new JsonResponse($jsonListPersonnes, Response::HTTP_OK, [], true);
    }
}
