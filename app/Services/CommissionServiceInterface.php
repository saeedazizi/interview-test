<?php

namespace App\Services;

use App\DTO\OperationDto;

interface CommissionServiceInterface
{
    public function calculate(OperationDto $dto): string;
}
