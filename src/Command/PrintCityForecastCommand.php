<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\MusementAPI;
use App\Service\WeatherAPI;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PrintCityForecastCommand extends SymfonyCommand
{
    private MusementAPI $musementAPI;
    private WeatherAPI $weatherAPI;

    public function __construct(MusementAPI $musementAPI, WeatherAPI $weatherAPI)
    {
        $this->musementAPI = $musementAPI;
        $this->weatherAPI = $weatherAPI;
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setName('print:city-forecast')
            ->setDescription('Print for each city the forecast for the next 2 days.')
            ->setHelp('Print for each city from Musement API the forecast for the next 2 days from Weather API.');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->printCitiesForecast($output);
    }

    private function printCitiesForecast(OutputInterface $output): int
    {
        $cities = $this->musementAPI->getCities();
        foreach ($cities as $city) {
            $city_forecasts = $this->weatherAPI->getForecastForLastDays($city->getLatitude(), $city->getLongitude());

            $text = sprintf(
                'Processed city %s | %s - %s',
                $city->getName(),
                $city_forecasts[0]->getText(),
                $city_forecasts[1]->getText()
            );
            $output->writeln($text);
        }

        return 0;
    }
}
