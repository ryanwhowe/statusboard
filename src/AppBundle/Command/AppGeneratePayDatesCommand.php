<?php

namespace AppBundle\Command;

use AppBundle\Entity\Calendar;
use Doctrine\Common\Persistence\ManagerRegistry;
use Statusboard\Utility\ArrayUtility;
use Statusboard\Utility\PayDate;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppGeneratePayDatesCommand extends ContainerAwareCommand
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
            ->setName('app:GeneratePayDates')
            ->setDescription('...')
            ->setDescription('Add pay dates to database')
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
        }
        foreach(range($min_year, $max_year) as $year) {

            $io->title("Processing Year ${year}");

            $national_holidays = $this->objectManager->getRepository(Calendar::class)->findBy(['type' => [Calendar::TYPE_NATIONAL_HOLIDAY]]);

            $holidays = ArrayUtility::formatArray($national_holidays, function($v){
                return $v->getEventDate()->format('Y-m-d');
            });
            $holidays = array_values(array_unique($holidays));

            $pay_dates = PayDate::generatePayDatesInYear($year, $holidays);

            $io->progressStart(count($pay_dates));
            foreach ($pay_dates as $date) {

                $existing_paydate = $this->objectManager->getRepository(Calendar::class)->findOneBy(['eventDate' => $date, 'type' => Calendar::TYPE_PAY_DATE]);
                if($existing_paydate === null) {
                    $calendar = new Calendar();
                    $this->objectManager->getManager()->persist($calendar);
                    $calendar->setEventDate($date);
                    $calendar->setType(Calendar::TYPE_PAY_DATE);
                    $this->objectManager->getManager()->flush();
                    $this->objectManager->getManager()->detach($calendar);
                }
                $io->progressAdvance();
            }
            $io->progressFinish();

        }

        $output->writeln('Command result.');
    }

    private function validateYear(string $year){
        if(substr($year, 0, 2) !== '20') return false;
        return true;
    }

}
