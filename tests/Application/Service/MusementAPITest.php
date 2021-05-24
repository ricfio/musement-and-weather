<?php

declare(strict_types=1);

namespace App\Tests\Application\Service;

use App\Service\MusementAPI;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class MusementAPITest extends KernelTestCase
{
    private MusementAPI $api;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$container;
        $service = $container->get('service.musement_api');
        $this->assertNotNull($service);
        $this->assertInstanceOf(MusementAPI::class, $service);
        if (is_object($service) && MusementAPI::class == get_class($service)) {
            $this->api = $service;
        }
        $this->assertInstanceOf(MusementAPI::class, $this->api);
    }

    public function testGetCityHasFoundMilanWithRightLatitudeAndLongitude(): void
    {
        $milan = $this->api->getCity(1);
        $this->assertIsArray($milan);
        $this->assertSame('Milan', $milan['name']);
        $this->assertSame(45.459, $milan['latitude']);
        $this->assertSame(9.183, $milan['longitude']);
    }

    public function testGetCitiesHasFoundManyCitiesIncludingRomeWithRightLatitudeAndLongitude(): void
    {
        $cities = $this->api->getCities();
        $this->assertIsArray($cities);
        $this->assertNotCount(0, $cities);
        $filtered_cities = array_filter($cities, fn ($city) => 'Rome' == $city['name']);
        $this->assertCount(1, $filtered_cities);
        $rome = current($filtered_cities);
        $this->assertSame('Rome', $rome['name']);
        $this->assertSame(41.898, $rome['latitude']);
        $this->assertSame(12.483, $rome['longitude']);
    }
}
