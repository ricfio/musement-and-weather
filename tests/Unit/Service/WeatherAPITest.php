<?php

declare(strict_types=1);

use App\Service\WeatherAPI;
use PHPUnit\Framework\TestCase;

final class WeatherAPITest extends TestCase
{
    public function testGetForecastReturnsLastTwoDays(): void
    {
        $api = new WeatherAPI();
        $forecastsForMilan = $api->getForecastForLastDays(45.459, 9.183);
        $this->assertIsArray($forecastsForMilan);
        $this->assertCount(2, $forecastsForMilan);
        $forecastsForRome = $api->getForecastForLastDays(41.898, 12.483);
        $this->assertIsArray($forecastsForRome);
        $this->assertCount(2, $forecastsForRome);
    }
}
