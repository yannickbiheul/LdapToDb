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

    public function __construct()
    {
        $this->annuaire = new AnnuaireManager();
    }

    // protected function configure(): void
    // {
    //     $this
    //         // configure an argument
    //         ->addArgument('username', InputArgument::REQUIRED, 'The username of the user')
    //         // ...
    //         ;
    // }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            $this->annuaire->callAnnuaire(),
        ]);

        // retrieve the argument value using getArgument()
        // $output->writeln('Username : ' . $input->getArgument('username'));

        return Command::SUCCESS;
    }
}