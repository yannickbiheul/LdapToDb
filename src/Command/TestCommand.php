<?php

namespace App\Command;

use App\Service\AnnuaireManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'app:test-command',
    description: 'tester une commande',
    hidden: false,
    aliases: ['app:command-test']
)]
class TestCommand extends Command
{
    private $annuaire;

    /**
     * Constructeur
     * Injection de AnnuaireManager
     */
    public function __construct(AnnuaireManager $annuaireManager)
    {
        $this->annuaire = $annuaireManager;
        parent::__construct();
    }

    /**
     * Configuration
     * Ajout d'arguments
     */
    protected function configure(): void
    {
        $this
            // configure an argument
            ->addArgument('username', InputArgument::REQUIRED, 'The username of the user')
            // ...
            ;
    }

    /**
     * Execute 
     * Retourne le numéro et si il est privé ou public
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sn = $input->getArgument('username');
        $sortie = $this->annuaire->findByName($sn);

        if (count($sortie) > 1) {
            $output->writeln([
                "----------",
                "Prénom : " . $sortie[0],
                "Nom : " . $sortie[1],
                "Numéro court : " . $sortie[2],
                "Numéro long : " . $sortie[3],
                "Email : " . $sortie[4],
                "Pôle : " . $sortie[5],
                "Métier : " . $sortie[6],
                "Poste : " . $sortie[7],
                "----------",
            ]);
        } else {
            $output->writeln([
                $sortie[0],
            ]);
        }

        return Command::SUCCESS;
    }
}