<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Forecast;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ForecastTest extends TestCase
{
    private Forecast $forecast;

    protected function setUp(): void
    {
        $this->forecast = new Forecast('Partly cloudy');
    }

    public function testCityInstance(): void
    {
        $this->assertInstanceOf(Forecast::class, $this->forecast);
    }

    public function testForecastText(): void
    {
        $this->assertSame('Partly cloudy', strval($this->forecast));
        $this->assertSame('Partly cloudy', $this->forecast->getText());
    }

    public function testForecastTextMustHaveAValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Forecast('');
    }
}
