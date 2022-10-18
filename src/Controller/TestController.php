<?php

namespace App\Controller;

use App\Service\AnnuaireManager;
use Symfony\Component\Ldap\Ldap;
use App\Service\ConnectLdapService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    private AnnuaireManager $annuaireManager;

    /**
     * Constructeur
     * Injection de AnnuaireManager
     */
    public function __construct(AnnuaireManager $annuaireManager) {
        $this->annuaireManager = $annuaireManager;
    }

    /**
     * Fonction index
     */
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        $sn = "PLOUHINEC";
        $tableauResultats = $this->annuaireManager->findPeopleByName($sn);
        dd($tableauResultats);
        for ($i=0; $i < count($tableauResultats); $i++) { 
            return $this->json([
                'Description' => 'Test des requêtes Ldap',
                'Requête' => 'Récupérer infos depuis le nom ' . $sn . '',
                'résultats' => [
                    'Prénom' => $tableauResultats[$i]->getPrenom(),
                    'Nom' => $tableauResultats[$i]->getNom(),
                    'Numéro court' => $tableauResultats[$i]->getNumeroCourt(),
                    'Numéro long' => $tableauResultats[$i]->getNumeroLong(),
                    'Mail' => $tableauResultats[$i]->getMail(),
                    'Pôle' => $tableauResultats[$i]->getPole(),
                    'Métier' => $tableauResultats[$i]->getMetier(),
                    'Poste' => $tableauResultats[$i]->getPoste(),
                ],
            ]);
        }

        
    }
}