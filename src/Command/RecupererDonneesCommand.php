<?php

namespace App\Command;

use App\Service\RecordManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:main',
    description: 'Enregistrer toutes les données Ldap dans la base de données',
    hidden: false,
    aliases: ['app:command-main']
)]
class RecupererDonneesCommand extends Command
{
    private RecordManager $recordManager;

    /**
     * Constructeur
     * Injection de PersonneManager
     */
    public function __construct(RecordManager $recordManager)
    {
        $this->recordManager = $recordManager;
        parent::__construct();
    }

    /**
     * Execute : 
     * Récupère les données et les envoie à la base de données
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->recordManager->enregistrerTout();

        return Command::SUCCESS;
    }
}