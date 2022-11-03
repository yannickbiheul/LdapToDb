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
        
        $this->hopitalManager->saveHopitaux();

        return $this->json([
            'resultat' => "TOUT VA BIEN !!!",
        ]);

    }

    /**
     * Stockage des fonctions de tests
     */
    public function testTest() {

        // Sauvegarder les hôpitaux
        $this->hopitalManager->saveHopitaux();
        // Sauvegarder les bâtiments
        $this->batimentManager->saveBatiments();
        // Sauvegarder les pôles
        $this->poleManager->savePoles();
        // Sauvegarder les métiers
        $this->metierManager->saveMetiers();
        // Sauvegarder les services
        $this->serviceManager->saveServices();
        // Sauvegarder les personnes
        $this->personneManager->savePersonnes();
    }
}