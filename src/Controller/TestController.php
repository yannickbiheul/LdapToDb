<?php

namespace App\Controller;

use App\Service\PersonneManager;
use App\Service\RecordManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    private PersonneManager $personneManager;
    private RecordManager $recordManager;

    /**
     * Constructeur
     * Injection de PersonneManager
     */
    public function __construct(PersonneManager $personneManager,
                                RecordManager $recordManager) {
        $this->personneManager = $personneManager;
        $this->recordManager = $recordManager;
    }

    /**
     * Fonction index
     */
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        dd($this->recordManager->listContactRecord());
        
        return $this->json([
            'resultat' => "TOUT VA BIEN !!!",
        ]);

    }

    /**
     * Stockage des fonctions de tests
     */
    public function testTest() {

        // Enregistrement de toutes les données "peopleRecord"
        $this->recordManager->enregistrerTout();
        // Enregistrer les personnes
        $this->personneManager->enregistrerPersonnes();
    }
}