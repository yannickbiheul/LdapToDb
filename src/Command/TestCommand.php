<?php

namespace App\Command;

use App\Service\PersonneManager;
use App\Service\PoleManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:test-command',
    description: 'tester une commande',
    hidden: false,
    aliases: ['app:command-test']
)]
class TestCommand extends Command
{
    private $personneManager;
    private $poleManager;

    /**
     * Constructeur
     * Injection de PersonneManager
     */
    public function __construct(PersonneManager $personneManager, PoleManager $poleManager)
    {
        $this->personneManager = $personneManager;
        $this->poleManager = $poleManager;
        parent::__construct();
    }

    /**
     * Execute : 
     * Récupère les tables et les envoie à la base de données Drupal
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Récupérer les pôles
        $this->poleManager->savePoles();
        
        // Récupérer les métiers

        // Récupérer les postes

        // Récupérer les personnes

        // Récupérer les contacts

        return Command::SUCCESS;
    }
}