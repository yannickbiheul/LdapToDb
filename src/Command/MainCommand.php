<?php

namespace App\Command;

use App\Service\PoleManager;
use App\Service\MetierManager;
use App\Service\HopitalManager;
use App\Service\BatimentManager;
use App\Service\PersonneManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:main',
    description: 'lancer une commande',
    hidden: false,
    aliases: ['app:command-main']
)]
class MainCommand extends Command
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
                                MetierManager $metierManager)
    {
        $this->personneManager = $personneManager;
        $this->poleManager = $poleManager;
        $this->batimentManager = $batimentManager;
        $this->hopitalManager = $hopitalManager;
        $this->metierManager = $metierManager;
        parent::__construct();
    }

    /**
     * Execute : 
     * Récupère les tables et les envoie à la base de données
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        
        return Command::SUCCESS;
    }
}