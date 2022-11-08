<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Repository\PersonneRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PersonneController extends AbstractController
{
    /**
     * Afficher la liste des personnes
     */
    #[Route('/api/personnes', name: 'personnes', methods: ['GET'])]
    public function index(PersonneRepository $personneRepository, SerializerInterface $serializer): JsonResponse
    {
        $listPersonnes = $personneRepository->findAll();
        $jsonListPersonnes = $serializer->serialize($listPersonnes, 'json', ['groups' => 'getPersonnes']);

        return new JsonResponse($jsonListPersonnes, Response::HTTP_OK, [], true);
    }

    /**
     * Afficher une personne depuis son id
     */
    #[Route('/api/personnes/{id}', name: 'detailPersonne', methods: ['GET'])]
    public function getDetailPersonne(Personne $personne, SerializerInterface $serializer): JsonResponse
    {
        $jsonPersonne = $serializer->serialize($personne, 'json', ['groups' => 'getPersonnes']);
        return new JsonResponse($jsonPersonne, Response::HTTP_OK, [], true);
    }

    /**
     * Afficher une personne depuis son nom
     */
    #[Route('/api/personnes/nom/{nom}', name: 'detailPersonne', methods: ['GET'])]
    public function getDetailPersonneByName(string $nom, PersonneRepository $personneRepository, SerializerInterface $serializer): JsonResponse
    {
        $listPersonnes = $personneRepository->findBy(['nom' => $nom]);
        $jsonPersonne = $serializer->serialize($listPersonnes, 'json', ['groups' => 'getPersonnes']);
        return new JsonResponse($jsonPersonne, Response::HTTP_OK, [], true);
    }
}
