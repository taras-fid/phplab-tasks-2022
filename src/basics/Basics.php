<?php

namespace basics;

class Basics implements  BasicsInterface
{
    protected BasicsValidator $validator;

    public function __construct(BasicsValidator $validator)
    {
        $this->validator = $validator;
    }

    public function getMinuteQuarter(int $minute): string
    {
        // TODO: Implement getMinuteQuarter() method.
        $this->validator->isMinutesException($minute);
        if ($minute > 45 || $minute === 0) { return 'fourth';}
        if ($minute > 30) { return 'third';}
        if ($minute > 15) { return 'second';}
        return 'first';
    }

    public function isLeapYear(int $year): bool
    {
        $this->validator->isYearException($year);
        if ($year > 2030) return false;
        return !($year & 1);
    }

    public function isSumEqual(string $input): bool
    {
        $this->validator->isValidStringException($input);
        $arr = str_split($input);
        $sum1 = $sum2 = 0;
        for ($i = 0; $i < 6; $i++) {
            if ($i < 3) {
                $sum1 += $arr[$i];
            }
            else {
                $sum2 += $arr[$i];
            }
        }
        if ($sum1 === $sum2) {
            return true;
        }
        else {
            return false;
        }
    }
}