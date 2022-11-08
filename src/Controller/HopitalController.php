<?php

namespace App\Controller;

use App\Entity\Hopital;
use App\Repository\HopitalRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HopitalController extends AbstractController
{
    /**
     * Afficher la liste des hôpitaux
     */
    #[Route('/api/hopitaux', name: 'hopitaux', methods: ['GET'])]
    public function index(HopitalRepository $hopitalRepository, SerializerInterface $serializer): JsonResponse
    {
        $listHopitaux = $hopitalRepository->findAll();
        $jsonListHopitaux = $serializer->serialize($listHopitaux, 'json', ['groups' => 'getHopitaux']);

        return new JsonResponse($jsonListHopitaux, Response::HTTP_OK, [], true);
    }

    /**
     * Afficher un hôpital
     */
    #[Route('/api/hopitaux/{id}', name: 'detailHopital', methods: ['GET'])]
    public function getDetailHopital(Hopital $hopital, SerializerInterface $serializer): JsonResponse
    {
        $jsonHopital = $serializer->serialize($hopital, 'json', ['groups' => 'getHopitaux']);
        return new JsonResponse($jsonHopital, Response::HTTP_OK, [], true);
    }
}
