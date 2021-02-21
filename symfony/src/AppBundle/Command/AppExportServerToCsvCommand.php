<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\Common\Persistence\ManagerRegistry;

use AppBundle\Entity\Server;

class AppExportServerToCsvCommand extends ContainerAwareCommand
{

    private $objectManager;

    public function __construct(ManagerRegistry $objectManager)
    {
        $this->objectManager = $objectManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:ExportServerToCsv')
            ->setDescription('Pull the contents of the server database to a csv file')
            ->addArgument('file', InputArgument::REQUIRED, 'Full path to csv File')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input,
            $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output);
        $file = $input->getArgument('file');

        if ( \file_exists($file)) {
            $io->error("${file} : already exists!");
        } else {
            $handle = fopen($file, 'w');
            $header = array('name', 'isDisabled');
            \fputcsv($handle, $header);
            $serversRepo = $this->objectManager->getRepository(Server::class);
            /* @var array */
            $servers = $serversRepo->findAll();
            $io->progressStart(count($servers));
            /* @var Server $server */
            foreach ($servers as $server) {

                \fputcsv($handle, array($server->getName(), (int)$server->getIsDisabled()));
                $io->progressAdvance();
            }
            \fclose($handle);

            $io->note('Write Process Completed');

        }
    }

}
