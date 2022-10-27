<?php

namespace App\Controller;

use App\Service\BatimentManager;
use App\Service\HopitalManager;
use App\Service\MetierManager;
use App\Service\PersonneManager;
use App\Service\PoleManager;
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

    /**
     * Constructeur
     * Injection de PersonneManager
     */
    public function __construct(PersonneManager $personneManager, 
                                PoleManager $poleManager, 
                                BatimentManager $batimentManager,
                                HopitalManager $hopitalManager,
                                MetierManager $metierManager) {
        $this->personneManager = $personneManager;
        $this->poleManager = $poleManager;
        $this->batimentManager = $batimentManager;
        $this->hopitalManager = $hopitalManager;
        $this->metierManager = $metierManager;
    }

    /**
     * Fonction index
     */
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        // Sauvegarder les hôpitaux
        $this->hopitalManager->saveHopitaux();
        // Sauvegarder les bâtiments
        $this->batimentManager->saveBatiments();

        // dd($this->poleManager->listPoles();
        // $this->batimentManager->saveBatiments();
        // dd($this->batimentManager->findHopital("DRM"));
        // dd($this->hopitalManager->listHopitaux());
        // $this->hopitalManager->saveHopitaux();
        // $this->batimentManager->saveBatiments();
        // dd($this->metierManager->listMetiers());
        // $this->personneManager->listPersonnes();
        
        return $this->json([
            'hopitaux' => $this->hopitalManager->listHopitaux(),
            'batiments' => $this->batimentManager->listBatiments(),
            'poles' => $this->poleManager->listPoles(),
        ]);

    }
}