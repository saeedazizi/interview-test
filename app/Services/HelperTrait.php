<?php

namespace App\Services;

use App\DTO\OperationDto;

trait HelperTrait
{
    public function roundUp(int|float $value): int|float
    {
        $subString = substr(strrchr((string)$value, "."), 1);
        $decimalDigits = strlen($subString);
        $count = 0; //count of zeros after decimal point
        for ($i = 0; $i < $decimalDigits; $i++) {
            if ($subString[$i] != 0) {
                break;
            }
            $count++;
        }

        return is_float($value) ? round($value, $count + 1) : $value;
    }

    public function formatOutput(int|float $result, OperationDto $dto): string
    {
        if (strpos($dto->getOperationAmount(), '.') == false) {
            return (int)$result;
        }

        if (strpos($dto->getOperationAmount(), '.') && strpos($result, '.') == false) {
            return $result . '.00';
        }

        return (string)$result;
    }
}
