<?php

namespace AppBundle\Command;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\Holiday;
use AppBundle\Entity\Server;
use AppBundle\Repository\HolidayRepository;
use Calendarific\Calendarific;
use Composer\Package\Package;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppImportHolidayCommand extends ContainerAwareCommand
{

    /**
     * @var ManagerRegistry
     */
    private $objectManager;

    /**
     * AppImportHolidayCommand constructor.
     * @param ManagerRegistry $objectManager
     */
    public function __construct(ManagerRegistry $objectManager)
    {
        $this->objectManager = $objectManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:ImportHoliday')
            ->setDescription('Add holidays from the Calenderific API provider for the passed year')
            ->addArgument('year', InputArgument::OPTIONAL, 'Year to import')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input,
            $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output);

        $year = $input->getArgument('year');

        if($this->validateYear($year)) {

            $holidays = Calendarific::make(
                '0a0d2b5a2250b5b98f36f734a1a029b3351f20b7'
                , 'US'
                , (int)$year
                , null
                , null
                , null
                , ['national']
            );
            $results = $holidays['response']['holidays'];
            $holidays = array_map(function($v){
                return [$v['date']['iso'] => $v['name']];
            }, $results);

            $io->progressStart(count($holidays));
            foreach ($holidays as $index => $holiday) {
                $date = key($holiday);

                $existing_holiday = $this->objectManager->getRepository(Holiday::class)->findOneBy(['observedDate' => new \DateTime($date)]);
                if($existing_holiday === null) {
                    $h = new Holiday();
                    $this->objectManager->getManager()->persist($h);

                    $h->setName($holiday[$date]);
                    $h->setObservedDate(new \DateTime($date));
                    $this->objectManager->getManager()->flush();
                    $this->objectManager->getManager()->detach($h);
                }
                $io->progressAdvance();
            }
            $io->progressFinish();

        } else {
            $io->error("${year} was not a valid year to process");
        }

        $output->writeln('Command result.');
    }

    private function validateYear(string $year){
        if(substr($year, 0, 2) !== '20') return false;
        return true;
    }

}
