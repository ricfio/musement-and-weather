<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\WeatherAPI;
use PHPUnit\Framework\TestCase;

final class WeatherAPITest extends TestCase
{
    private WeatherAPI $api;

    protected function setUp(): void
    {
        $_ENV['WEATHER_API_URL'] = 'https://api.weatherapi.com/v1';
        $_ENV['WEATHER_API_KEY'] = '8f6949cbe8934b1cb87235108212105';
        $this->api = new WeatherAPI($_ENV['WEATHER_API_URL'], $_ENV['WEATHER_API_KEY']);
    }

    public function testGetForecastReturnsLastTwoDays(): void
    {
        $forecastsForMilan = $this->api->getForecastForLastDays(45.459, 9.183);
        $this->assertIsArray($forecastsForMilan);
        $this->assertCount(2, $forecastsForMilan);
        $forecastsForRome = $this->api->getForecastForLastDays(41.898, 12.483);
        $this->assertIsArray($forecastsForRome);
        $this->assertCount(2, $forecastsForRome);
    }
}
