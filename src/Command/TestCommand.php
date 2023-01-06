<?php

namespace App\Command;

use App\Service\PersonneManager;
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
    private PersonneManager $personneManager;

    /**
     * Constructeur
     * Injection de PersonneManager
     */
    public function __construct(PersonneManager $personneManager)
    {
        $this->personneManager = $personneManager;
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