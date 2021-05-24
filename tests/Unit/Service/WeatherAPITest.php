<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\WeatherAPI;
use PHPUnit\Framework\TestCase;

final class WeatherAPITest extends TestCase
{
    public function testGetForecastReturnsLastTwoDays(): void
    {
        $_ENV['WEATHER_API_KEY'] = '8f6949cbe8934b1cb87235108212105';
        $api = new WeatherAPI($_ENV['WEATHER_API_KEY']);

        $forecastsForMilan = $api->getForecastForLastDays(45.459, 9.183);
        $this->assertIsArray($forecastsForMilan);
        $this->assertCount(2, $forecastsForMilan);
        $forecastsForRome = $api->getForecastForLastDays(41.898, 12.483);
        $this->assertIsArray($forecastsForRome);
        $this->assertCount(2, $forecastsForRome);
    }
}
