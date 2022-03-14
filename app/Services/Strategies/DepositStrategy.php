<?php

namespace App\Services\Strategies;

use App\DTO\OperationDto;

final class DepositStrategy extends AbstractStrategy
{
    public function isEligible(OperationDto $dto): bool
    {
        return $dto->getOperationType() === 'deposit';
    }

    public function supply(OperationDto $dto): string
    {
        return $this->formatOutput($this->roundUp($dto->getOperationAmount() * 0.0003), $dto);
    }
}
