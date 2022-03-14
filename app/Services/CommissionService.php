<?php

namespace App\Services;

use App\DTO\OperationDto;
use App\Exceptions\OperationTypeException;
use App\Services\Strategies\CommissionStrategyInterface;

final class CommissionService implements CommissionServiceInterface
{
    public function __construct(private iterable $strategies)
    {
    }

    public function calculate(OperationDto $dto): string
    {
        /** @var CommissionStrategyInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->isEligible($dto)) {
                return $strategy->supply($dto);
            }
        }

        throw new OperationTypeException('Operation type is not valid!');
    }
}
