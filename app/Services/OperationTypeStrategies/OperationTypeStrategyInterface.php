<?php

namespace App\Services\OperationTypeStrategies;

use App\DTO\OperationDto;

interface OperationTypeStrategyInterface
{
    public function isEligible(OperationDto $dto): bool;

    public function supply(OperationDto $dto): string;
}
