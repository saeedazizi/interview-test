<?php

namespace App\Services\UserStrategies;

use App\DTO\OperationDto;

class BusinessUser extends AbstractUserStrategy
{
    public function isEligible(OperationDto $dto): bool
    {
        return $dto->getUserType() === 'business';
    }

    public function supply(OperationDto $dto): string
    {
        return $this->formatOutput($this->roundUp($dto->getOperationAmount() * 0.005), $dto);
    }
}
