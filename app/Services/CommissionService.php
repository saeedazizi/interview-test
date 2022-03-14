<?php

namespace App\Services;

use App\DTO\OperationDto;
use App\Exceptions\OperationTypeException;
use App\Services\OperationTypeStrategies\OperationTypeStrategyInterface;

final class CommissionService implements CommissionServiceInterface
{
    public function __construct(private iterable $strategies)
    {
    }

    public function calculate(OperationDto $dto): string
    {
        /** @var OperationTypeStrategyInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->isEligible($dto)) {
                return $strategy->supply($dto);
            }
        }

        throw new OperationTypeException('Operation type is not valid!');
    }
}
