<?php

namespace App\Services\OperationTypeStrategies;

use App\DTO\OperationDto;

final class DepositStrategy extends AbstractOperationTypeStrategy
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
