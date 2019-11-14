<?php declare(strict_types=1);

namespace AppBundle\Command;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\Server;
use Calendarific\Calendarific;
use Doctrine\Common\Persistence\ManagerRegistry;
use Statusboard\Utility\ArrayUtility;
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

        $file = $input->getArgument('calendar_file');

        if ($file !== \null) {

            if ($file === \null || !\file_exists($file)) {
                $io->error("${file} : does not exists");
            } else {

                $csv = ArrayUtility::csvToAssociativeArray(file($file));

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

                    $csv = ArrayUtility::csvToAssociativeArray(file($file));

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
}
