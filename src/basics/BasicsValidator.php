<?php

namespace basics;

use http\Exception\InvalidArgumentException;
use phpDocumentor\Reflection\Types\Integer;
use function PHPUnit\Framework\isType;

class BasicsValidator implements BasicsValidatorInterface
{

    public function __construct()
    {
    }

    public function isMinutesException(int $minute): void
    {
        if (!isset($minute) || $minute < 0 || $minute > 60) {
            throw new \InvalidArgumentException();
        }
    }

    public function isYearException(int $year): void
    {
        if (!isset($year) || $year < 1900) {
            throw new \InvalidArgumentException();
        }
    }

    public function isValidStringException(string $input): void
    {
        if (!isset($input) || strlen($input) != 6) {
            throw new \InvalidArgumentException();
        }
    }
}