<?php

namespace App\Services\OperationTypeStrategies;

use App\DTO\OperationDto;
use App\Exceptions\ClientTypeException;
use App\Services\UserStrategies\UserStrategyInterface;

final class WithdrawStrategy extends AbstractOperationTypeStrategy
{
    public function __construct(private iterable $stages)
    {
    }

    public function isEligible(OperationDto $dto): bool
    {
        return $dto->getOperationType() === 'withdraw';
    }

    public function supply(OperationDto $dto): string
    {
        /** @var UserStrategyInterface $stage */
        foreach ($this->stages as $stage) {
            if ($stage->isEligible($dto)) {
                return $stage->supply($dto);
            }
        }

        throw new ClientTypeException('Client type is not valid!');
    }
}
