<?php

namespace AppBundle\Command;

use Doctrine\Common\Persistence\ManagerRegistry;
use Statusboard\Weather\Accuweather\Transform;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GetWeatherImagesCommand extends ContainerAwareCommand
{

    CONST MIN_ICON_NUMBER = 1;
    CONST MAX_ICON_NUMBER = 44;

    CONST WEATHER_DIRECTORY_BASE = 'web';
    CONST WEATHER_DIRECTORY_ACCUWEATHER = self::WEATHER_DIRECTORY_BASE . Transform::ICON_BASE_DIRECTORY;

    protected function configure()
    {
        $this
            ->setName('app:GetWeatherImages')
            ->setDescription('Download the weather images')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input,
            $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output);

        $io->title("Checking Weather Directories");

        $skip_numbers = [10,28,27,9];

        $base_dir = $this->getContainer()->getParameter('kernel.project_dir');
        $base_dir .= '/' . self::WEATHER_DIRECTORY_ACCUWEATHER;
        if(!file_exists($base_dir)){
            $io->error("Missing Destination directory : " . $base_dir);
            $io->caution("Creating directory " . $base_dir);
            mkdir($base_dir,0777,true);
        }

        $io->title("Processing Accuweather");
        $io->progressStart(self::MAX_ICON_NUMBER);
        foreach(range(self::MIN_ICON_NUMBER,self::MAX_ICON_NUMBER) as $icon_number){
            if(in_array($icon_number, $skip_numbers)) {
                $io->progressAdvance();
                continue;
            }
            $url = $this->generateAccuweatherIconImageUrl($icon_number);
            $img = $base_dir . Transform::generateIconFileName($icon_number);

            file_put_contents($img, file_get_contents($url));

            $io->progressAdvance();
        }
        $io->progressFinish();
        $io->success("Process Accuweather Completed");


    }
    /**
     * @param int $icon
     * @return string
     */
    public static function generateAccuweatherIconImageUrl(int $icon): string {
        return 'https://developer.accuweather.com/sites/default/files/' . Transform::generateIconFileName($icon);
    }

}
