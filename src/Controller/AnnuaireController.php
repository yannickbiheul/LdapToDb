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
        // Créer une instance de SearchData ainsi que son formulaire
        $searchData = new SearchData();
        $form = $this->createForm(SearchDataType::class, $searchData);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // "Découpage de la valeur envoyée depuis le formulaire en 2 parties
            $requeteComplete = explode(' ', $form->getData()->getName(), 2);
            // La 1ère partie pour le prénom
            $requetePrenom = $requeteComplete[0];
            // La 2ème partie pour le nom
            $requeteNom = $requeteComplete[1];
            // Recherche par le repo en donnant le prénom et le nom
            $resultat = $peopleRecordRepository->findBy(['sn' => $requeteNom, 'displayGn' => $requetePrenom],['displayGn' => 'ASC']);

            // Retour sur la page avec les résultats
            return $this->render('annuaire/index.html.twig', [
                'controller_name' => 'AnnuaireController',
                'search_form' => $form->createView(),
                'resultat' => $resultat,
            ]);
        }

        // Si le formulaire n'est pas soumis
        return $this->render('annuaire/index.html.twig', [
            'controller_name' => 'AnnuaireController',
            'search_form' => $form->createView(),
        ]);
    }
}
