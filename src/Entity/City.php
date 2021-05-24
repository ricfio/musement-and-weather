<?php

declare(strict_types=1);

namespace App\Entity;

use InvalidArgumentException;
use Stringable;

class City implements Stringable
{
    private int $id;
    private string $name;
    private float $latitude;
    private float $longitude;

    public function __construct(int $id, string $name, float $latitude, float $longitude)
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('city id must be greater than zero');
        }
        if (0 == strlen($name)) {
            throw new InvalidArgumentException('city name must have a value');
        }
        $this->id = $id;
        $this->name = $name;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    final public function __toString()
    {
        return $this->name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }
}
