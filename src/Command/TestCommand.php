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
    private $personneManager;

    /**
     * Constructeur
     * Injection de PersonneManager
     */
    public function __construct(PersonneManager $personneManager)
    {
        $this->personne = $personneManager;
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
     * Retourne les infos d'une personne depuis un nom
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sn = $input->getArgument('username');
        $sortie = $this->annuaire->findByName($sn);

        if (count($sortie) > 1) {
            for ($i=0; $i < count($sortie); $i++) { 
                $output->writeln([
                    "----------",
                    "Prénom : " . $sortie[$i]->prenom,
                    "Nom : " . $sortie[$i]->nom,
                    "Numéro court : " . $sortie[$i]->numeroCourt,
                    "Numéro long : " . $sortie[$i]->numeroLong,
                    "Email : " . $sortie[$i]->mail,
                    "Pôle : " . $sortie[$i]->pole,
                    "Métier : " . $sortie[$i]->metier,
                    "Poste : " . $sortie[$i]->poste,
                    "----------",
                ]);
            }
            
        } else {
            $output->writeln([
                $sortie[0],
            ]);
        }

        return Command::SUCCESS;
    }
}