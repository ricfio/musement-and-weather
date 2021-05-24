<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\City;
use App\Service\MusementAPI;
use PHPUnit\Framework\TestCase;

final class MusementAPITest extends TestCase
{
    private MusementAPI $api;

    protected function setUp(): void
    {
        $_ENV['MUSEMENT_API_URL'] = 'https://sandbox.musement.com/api/v3';
        $this->api = new MusementAPI($_ENV['MUSEMENT_API_URL']);
    }

    public function testGetCityHasFoundMilanWithRightLatitudeAndLongitude(): void
    {
        $milan = $this->api->getCity(1);
        $this->assertInstanceOf(City::class, $milan);
        $this->assertSame('Milan', $milan->getName());
        $this->assertSame(45.459, $milan->getLatitude());
        $this->assertSame(9.183, $milan->getLongitude());
    }

    public function testGetCitiesHasFoundManyCitiesIncludingRomeWithRightLatitudeAndLongitude(): void
    {
        $cities = $this->api->getCities();
        $this->assertIsArray($cities);
        $this->assertNotCount(0, $cities);
        $filtered_cities = array_filter($cities, fn ($city) => 'Rome' == $city->getName());
        $this->assertCount(1, $filtered_cities);
        $rome = current($filtered_cities);
        $this->assertInstanceOf(City::class, $rome);
        if (is_object($rome) && City::class == get_class($rome)) {
            $this->assertSame('Rome', $rome->getName());
            $this->assertSame(41.898, $rome->getLatitude());
            $this->assertSame(12.483, $rome->getLongitude());
        }
    }
}
