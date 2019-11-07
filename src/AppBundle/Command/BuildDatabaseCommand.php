<?php declare(strict_types=1);

namespace AppBundle\Command;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\Holiday;
use AppBundle\Entity\Server;
use Calendarific\Calendarific;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BuildDatabaseCommand extends ContainerAwareCommand
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
            ->setName('app:buildDatabase')
            ->setDescription('Build the Calendar database')
            ->addArgument('calendar_file', InputArgument::OPTIONAL, 'Full path to calendar csv file')
            ->addArgument('server_file', InputArgument::OPTIONAL, 'Full path to server csv file');;
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

        $holidays = Calendarific::make(
            $this->getContainer()->getParameter('calendarific_api_key')
            ,'US'
            ,(int)date('Y')
            ,null
            ,null
            ,null
            ,['national']
        );
        $results = $holidays['response']['holidays'];

        $holidays = array_map(function($v){
            return [$v['date']['iso'] => $v['name']];
        }, $results);

        $io->progressStart(count($holidays));
        foreach ($holidays as $index => $holiday) {
            $h = new Holiday();
            $this->objectManager->getManager()->persist($h);
            $date = key($holiday);
            $h->setName($holiday[$date]);
            $h->setObservedDate(new \DateTime($date));
            $this->objectManager->getManager()->flush();
            $this->objectManager->getManager()->detach($h);
            $io->progressAdvance();
        }
        $io->progressFinish();

        $file = $input->getArgument('calendar_file');
        $io->note('Holiday Load Process Completed');

        if ($file !== \null) {

            if ($file === \null || !\file_exists($file)) {
                $io->error("${file} : does not exists");
            } else {

                $csv = $this->CsvToAssociativeArray($file);

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
                $io->progressFinish();

                $io->note('Calendar Load Process Completed');
            }

            $file = $input->getArgument('server_file');

            if ($file !== \null) {

                if ($file === \null || !\file_exists($file)) {
                    $io->error("${file} : does not exists");
                } else {

                    $csv = $this->CsvToAssociativeArray($file);

                    $io->progressStart(count($csv));
                    foreach ($csv as $row) {
                        $server = new Server();
                        $this->objectManager->getManager()->persist($server);
                        $server->setName($row['name']);
                        $server->setIsDisabled((bool)$row['isDisabled']);
                        $this->objectManager->getManager()->flush();
                        $this->objectManager->getManager()->detach($server);
                        $io->progressAdvance();
                    }
                    $io->progressFinish();

                    $io->note('Server Load Process Completed');
                }
            }
        }
    }

    /**
     * @param $file
     * @return array
     */
    protected function CsvToAssociativeArray($file): array
    {
        $csv = array_map('str_getcsv', file($file));
        \array_walk($csv, function (&$a) use ($csv) {
            $a = \array_combine($csv[0], $a);
        });
        array_shift($csv);
        return $csv;
    }

}
