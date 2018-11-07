<?php declare(strict_types=1);

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\ArrayInput;

class BuildDatabaseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:buildDatabase')
            ->setDescription('Build the Calendar database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input,
            $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output);

        $io->title('Dropping Database file');
        $command = $this->getApplication()->find('doctrine:database:drop');

        $arguments = [
            'command' => 'doctrine:database:drop',
            '--force' => true,
        ];

        $greetInput = new ArrayInput($arguments);
        $returnCode = $command->run($greetInput, $output);

        $io->title('Creating Database file');
        $command = $this->getApplication()->find('doctrine:database:create');

        $arguments = [
            'command' => 'doctrine:database:create',
        ];

        $greetInput = new ArrayInput($arguments);
        $returnCode = $command->run($greetInput, $output);

        $io->title('Updating Database file schema');
        $command = $this->getApplication()->find('doctrine:schema:update');

        $arguments = [
            'command' => 'doctrine:schema:update',
            '--force' => true,
        ];

        $greetInput = new ArrayInput($arguments);
        $returnCode = $command->run($greetInput, $output);

    }

}
