<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\City;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CityTest extends TestCase
{
    private City $milan;

    protected function setUp(): void
    {
        $this->milan = new City(1, 'Milan', 45.459, 9.183);
    }

    public function testCityInstance(): void
    {
        $this->assertInstanceOf(City::class, $this->milan);
    }

    public function testCityId(): void
    {
        $this->assertSame(1, $this->milan->getId());
    }

    public function testCityIdMustBeGreaterThanZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new City(0, 'Unknown city', 12.345, 67.890);
    }

    public function testCityName(): void
    {
        $this->assertSame('Milan', $this->milan->getName());
    }

    public function testCityNameMustHaveAValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new City(999, '', 12.345, 67.890);
    }

    public function testCityLatitude(): void
    {
        $this->assertSame(45.459, $this->milan->getLatitude());
    }

    public function testCityLongitude(): void
    {
        $this->assertSame(9.183, $this->milan->getLongitude());
    }
}
