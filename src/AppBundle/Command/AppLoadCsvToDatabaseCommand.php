<?php declare(strict_types=1);

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

class AppLoadCsvToDatabaseCommand extends ContainerAwareCommand
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
            ->setName('app:loadCsvToDatabase')
            ->setDescription('Load the calendar database with data from a csv file, file is expected to have a header row of "type","event_date"')
            ->addArgument('file', InputArgument::REQUIRED, 'Full path to csv File')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output);
        $file = $input->getArgument('file');

        if(!\file_exists($file)){
            $io->error("${file} : does not exists");
        } else {

            $csv = array_map('str_getcsv', file($file));
            \array_walk($csv, function (&$a) use ($csv) {
                $a = \array_combine($csv[0], $a);
            });
            array_shift($csv);

            $io->progressStart(count($csv));
            foreach ($csv as $row) {
                $calendar = new Calendar();
                $this->objectManager->getManager()->persist($calendar);
                $calendar->setType($row['type']);
                $calendar->setEventDate(new \DateTime($row['event_date']));
                $this->objectManager->getManager()->flush();
                $this->objectManager->getManager()->detach($calendar);
                $io->progressAdvance();
            }

            $io->note('Load Process Completed');
        }
    }

}
