<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * Afficher la liste des contacts
     */
    #[Route('/api/contacts', name: 'contacts', methods: ['GET'])]
    public function index(ContactRepository $contactRepository, SerializerInterface $serializer): JsonResponse
    {
        $listContacts = $contactRepository->findAll();
        $jsonListContacts = $serializer->serialize($listContacts, 'json', ['groups' => 'getContacts']);

        return new JsonResponse($jsonListContacts, Response::HTTP_OK, [], true);
    }

    /**
     * Afficher un contact
     */
    #[Route('/api/contacts/{id}', name: 'detailContact', methods: ['GET'])]
    public function getDetailContact(Contact $contact, SerializerInterface $serializer): JsonResponse
    {
        $jsonContact = $serializer->serialize($contact, 'json', ['groups' => 'getContacts']);
        return new JsonResponse($jsonContact, Response::HTTP_OK, [], true);
    }
}
