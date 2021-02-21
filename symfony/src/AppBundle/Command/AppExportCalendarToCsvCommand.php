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

use AppBundle\Entity\Calendar;

class AppExportCalendarToCsvCommand extends ContainerAwareCommand
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
            ->setName('app:ExportCalendarToCsv')
            ->setDescription('Pull the contents of the database to a csv file')
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
            $header = array('type', 'event_date');
            \fputcsv($handle, $header);
            $calendars = $this->objectManager->getRepository(Calendar::class);
            /* @var array */
            $events = $calendars->findAll();
            $io->progressStart(count($events));
            /* @var Calendar $event */
            foreach ($events as $event) {
                \fputcsv($handle, array($event->getType(), $event->getEventDate()->format("Y-m-d")));
                $io->progressAdvance();
            }
            \fclose($handle);

            $io->note('Write Process Completed');

        }
    }

}
