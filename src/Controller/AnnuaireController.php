<?php

namespace App\Controller;

use App\Entity\SearchData;
use App\Form\SearchDataType;
use App\Repository\HopitalRepository;
use App\Repository\PeopleRecordRepository;
use App\Repository\PersonneRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Annuaire de l'hôpital
 * 3 onglets : Annuaire, Procédures dégradées Dect, gardes - Astreintes
 */
class AnnuaireController extends AbstractController
{
    #[Route('/', name: 'app_annuaire')]
    public function index(Request $request, PeopleRecordRepository $peopleRecordRepository): Response
    {
        $searchData = new SearchData();
        $form = $this->createForm(SearchDataType::class, $searchData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $requete = strtoupper($form->getData()->getName());
            $resultat = $peopleRecordRepository->findBy(['sn' => $requete],['displayGn' => 'ASC']);

            return $this->render('annuaire/index.html.twig', [
                'controller_name' => 'AnnuaireController',
                'search_form' => $form->createView(),
                'resultat' => $resultat,
            ]);
        }

        return $this->render('annuaire/index.html.twig', [
            'controller_name' => 'AnnuaireController',
            'search_form' => $form->createView(),
        ]);
    }
}
