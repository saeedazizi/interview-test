<?php

namespace App\Services\Strategies;

use App\DTO\OperationDto;

interface CommissionStrategyInterface
{
    public function isEligible(OperationDto $dto): bool;

    public function supply(OperationDto $dto): string;
}
