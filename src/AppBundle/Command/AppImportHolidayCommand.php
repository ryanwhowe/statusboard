<?php

namespace AppBundle\Command;

use AppBundle\Entity\Calendar;
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
            ->addArgument('year', InputArgument::OPTIONAL, 'Year to import', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input,
            $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output);

        $requested_year = $input->getArgument('year');
        if($requested_year){
            $min_year = $max_year = (int)$requested_year;
        } else {
            $min_year = 2007;
            $max_year = (int)date('Y');
            $max_year += 1;
        }

        foreach(range($min_year, $max_year) as $year) {

            $io->title("Processing Year ${year}");

            $holidays = Calendarific::make(
                $this->getContainer()->getParameter('calendarific_api_key')
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

            //dump($holidays); die();

            $io->progressStart(count($holidays));
            foreach ($holidays as $index => $holiday) {
                $date = key($holiday);

                $existing_holiday = $this->objectManager->getRepository(Calendar::class)->findOneBy(['eventDate' => new \DateTime($date), 'type' => Calendar::TYPE_NATIONAL_HOLIDAY]);
                if($existing_holiday === null) {
                    $calendar = new Calendar();
                    $this->objectManager->getManager()->persist($calendar);

                    $calendar->setDescription($holiday[$date]);
                    $calendar->setEventDate(new \DateTime($date));
                    $calendar->setType(Calendar::TYPE_NATIONAL_HOLIDAY);
                    $this->objectManager->getManager()->flush();
                    $this->objectManager->getManager()->detach($calendar);
                }
                $io->progressAdvance();
            }
            $io->progressFinish();

        }
        $output->writeln('Command result.');
    }

}
