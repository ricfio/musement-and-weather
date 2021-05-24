<?php

declare(strict_types=1);

namespace App\Entity;

use InvalidArgumentException;
use Stringable;

class Forecast implements Stringable
{
    private string $text;

    public function __construct(string $text)
    {
        if (0 == strlen($text)) {
            throw new InvalidArgumentException('forecast text must have a value');
        }
        $this->text = $text;
    }

    final public function __toString()
    {
        return $this->text;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
