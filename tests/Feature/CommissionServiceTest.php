<?php

namespace Tests\Feature;

use App\DTO\OperationDto;
use App\Services\CommissionService;
use App\Services\CommissionServiceInterface;
use Tests\TestCase;

class CommissionServiceTest extends TestCase
{
    public function testServiceWorkCorrectly(): void
    {
        $expected = [
            '0.6',
            '3.00',
            '0.00',
            '0.06',
            '1.5',
            '0',
            '0.7',
            '0.3',
            '0.3',
            '3.00',
            '0.00',
            '0.00',
            '8607',
        ];

        /** @var CommissionService $service */
        $service = $this->app->make(CommissionServiceInterface::class);
        $handle = fopen(base_path() . "/tests/test.csv", "r");
        $raw = 0;
        while (($data = fgetcsv($handle, 1000)) !== false) {
            $dto = (new OperationDto())
                ->setDate($data[0])
                ->setUserIdentifier($data[1])
                ->setUserType($data[2])
                ->setOperationType($data[3])
                ->setOperationAmount($data[4])
                ->setOperationCurrency($data[5]);

            self::assertEquals($expected[$raw], $service->calculate($dto));

            $raw++;
        }
        fclose($handle);
    }
}
