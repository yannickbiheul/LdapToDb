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
        $sn = "TAGLIAFERRO";
        $tableauTest = $this->annuaireManager->findByName($sn);

        dd($tableauTest);

        return $this->json([
            'message' => 'Test des requêtes Ldap',
            'path' => 'src/Controller/TestController.php',
        ]);
    }
}