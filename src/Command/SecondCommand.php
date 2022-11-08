<?php

namespace App\Command;

use App\Service\BatimentManager;
use App\Service\HopitalManager;
use App\Service\MetierManager;
use App\Service\PersonneManager;
use App\Service\PoleManager;
use App\Service\ServiceManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:second',
    description: 'Placer toutes les données dans les tables',
    hidden: false,
    aliases: ['app:command-second']
)]
class SecondCommand extends Command
{

    private HopitalManager $hopitalManager;
    private BatimentManager $batimentManager;
    private PoleManager $poleManager;
    private MetierManager $metierManager;
    private ServiceManager $serviceManager;
    private PersonneManager $personneManager;

    /**
     * Constructeur
     * Injection de PersonneManager
     */
    public function __construct(HopitalManager $hopitalManager,
                                BatimentManager $batimentManager,
                                PoleManager $poleManager,
                                MetierManager $metierManager,
                                ServiceManager $serviceManager,
                                PersonneManager $personneManager)
    {
        $this->hopitalManager = $hopitalManager;
        $this->batimentManager = $batimentManager;
        $this->poleManager = $poleManager;
        $this->metierManager = $metierManager;
        $this->serviceManager = $serviceManager;
        $this->personneManager = $personneManager;
        parent::__construct();
    }

    /**
     * Execute : 
     * Récupère les données et les envoie à la base de données
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Enregistrement des hôpitaux
        $this->hopitalManager->enregistrerHopitaux();
        // Enregistrement des bâtiments
        $this->batimentManager->enregistrerBatiments();
        // Enregistrer les pôles
        $this->poleManager->enregistrerPoles();
        // Enregistrement des métiers
        $this->metierManager->enregistrerMetiers();
        // Enregistrer les services
        $this->serviceManager->enregistrerServices();
        // Enregistrer les personnes
        $this->personneManager->enregistrerPersonnes();

        return Command::SUCCESS;
    }
}