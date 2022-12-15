<?php

namespace App\Controller;

use App\Service\PoleManager;
use App\Service\MetierManager;
use App\Service\HopitalManager;
use App\Service\ServiceManager;
use App\Service\BatimentManager;
use App\Service\PersonneManager;
use App\Service\RecordManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    private PersonneManager $personneManager;
    private PoleManager $poleManager;
    private BatimentManager $batimentManager;
    private HopitalManager $hopitalManager;
    private MetierManager $metierManager;
    private ServiceManager $serviceManager;
    private RecordManager $recordManager;

    /**
     * Constructeur
     * Injection de PersonneManager
     */
    public function __construct(PersonneManager $personneManager, 
                                PoleManager $poleManager, 
                                BatimentManager $batimentManager,
                                HopitalManager $hopitalManager,
                                MetierManager $metierManager,
                                ServiceManager $serviceManager,
                                RecordManager $recordManager) {
        $this->personneManager = $personneManager;
        $this->poleManager = $poleManager;
        $this->batimentManager = $batimentManager;
        $this->hopitalManager = $hopitalManager;
        $this->metierManager = $metierManager;
        $this->serviceManager = $serviceManager;
        $this->recordManager = $recordManager;
    }

    /**
     * Fonction index
     */
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        $this->recordManager->enregistrerContactRecord();
        $this->recordManager->enregistrerNumberRecord();
        $this->recordManager->enregistrerPeopleRecord();
        
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
        // Enregistrement des hôpitaux
        $this->hopitalManager->enregistrerHopitaux();
        // Enregistrement des bâtiments
        $this->batimentManager->enregistrerBatiments();
        // Enregistrer les pôles
        $this->poleManager->enregistrerPoles();
        // Enregistrer les services
        $this->serviceManager->enregistrerServices();
        // Enregistrer les personnes
        $this->personneManager->enregistrerPersonnes();
    }
}