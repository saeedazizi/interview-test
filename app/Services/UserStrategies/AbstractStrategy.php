<?php

namespace App\Services\UserStrategies;

use App\Services\HelperTrait;

abstract class AbstractStrategy implements UserStrategyInterface
{
    use HelperTrait;

    private static array $history = [];

    protected function setHistory(int $userIdentifier, string $date, float $amount): void
    {
        if (isset(self::$history[$userIdentifier][$date])) {
            self::$history[$userIdentifier][$date] += $amount;
        } else {
            self::$history[$userIdentifier][$date] = $amount;
        }
    }

    protected function getHistory(int $userIdentifier): array
    {
        return self::$history[$userIdentifier] ?? [];
    }
}
