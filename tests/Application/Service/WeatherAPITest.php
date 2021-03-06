<?php

declare(strict_types=1);

namespace App\Tests\Application\Service;

use App\Service\WeatherAPI;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class WeatherAPITest extends KernelTestCase
{
    private WeatherAPI $api;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$container;
        $service = $container->get('service.weather_api');
        $this->assertNotNull($service);
        $this->assertInstanceOf(WeatherAPI::class, $service);
        /* @phpstan-ignore-next-line */
        $this->api = $service;
    }

    public function testGetForecastReturnsLastTwoDays(): void
    {
        $forecastsForMilan = $this->api->getForecastForLastDays(45.459, 9.183);
        $this->assertCount(2, $forecastsForMilan);
        $forecastsForRome = $this->api->getForecastForLastDays(41.898, 12.483);
        $this->assertCount(2, $forecastsForRome);
    }
}
