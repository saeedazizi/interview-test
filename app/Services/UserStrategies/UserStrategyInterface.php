<?php

namespace App\Services\UserStrategies;

use App\DTO\OperationDto;

interface UserStrategyInterface
{
    public function isEligible(OperationDto $dto): bool;

    public function supply(OperationDto $dto): string;
}
