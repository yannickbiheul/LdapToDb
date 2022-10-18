<?php

namespace App\Controller;

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

    /**
     * Constructeur
     * Injection de PersonneManager
     */
    public function __construct(PersonneManager $personneManager, PoleManager $poleManager) {
        $this->personneManager = $personneManager;
        $this->poleManager = $poleManager;
    }

    /**
     * Fonction index
     */
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        $poles = $this->poleManager->getPoles();
        $tableauPoles = array();
        dd($poles);
        
        return $this->json([

        ]);

    }
}