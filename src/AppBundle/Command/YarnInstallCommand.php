<?php declare(strict_types=1);
/**
 * This file contains the definition for the YarnInstallCommand class
 *
 * @author Ryan Howe
 * @since  2018-06-03
 */

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class YarnInstallCommand extends Command
{

    protected $lock_file;

    protected function configure()
    {
        $this->
        setName('app:yarninstall')->
        setDescription('Install the Yarn Dependencies')->
        setHelp(<<<'EOF'
<info>%command.name%</info> will remove the <comment>yarn.lock</comment> file then install the yarn dependencies to the <comment>web/bundle</comment> folder.

EOF
        );

        $this->lock_file = 'yarn.lock';
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new SymfonyStyle($input,
            $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output);

        $io->title("Removing yarn lock file");
        if (file_exists($this->lock_file)) {
            $io->success("yarn lock file found");
            if (unlink($this->lock_file)) {
                $io->success("yarn lock file removed");
            } else {
                $io->error("yarn lock file could not be removed, check your permissions");
            }
        }

        $process = new Process('yarn --modules-folder web/bundles');
        $process->start();
        foreach ($process as $type => $out) {
            $output->write($out);
        }
        $io->success('yarn dependencies have been installed');
    }
}