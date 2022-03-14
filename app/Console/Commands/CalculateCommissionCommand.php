<?php

namespace App\Console\Commands;

use App\DTO\OperationDto;
use App\Services\CommissionServiceInterface;
use Illuminate\Console\Command;

class CalculateCommissionCommand extends Command
{
    protected $signature = 'commission:calculate {file}';

    protected $description = 'This command calculate commission base on input csv file';

    public function handle(CommissionServiceInterface $service): int
    {
        if (($handle = fopen($this->argument('file'), "r")) !== false) {
            while (($data = fgetcsv($handle, 1000)) !== false) {
                $dto = (new OperationDto())
                    ->setDate($data[0])
                    ->setUserIdentifier($data[1])
                    ->setUserType($data[2])
                    ->setOperationType($data[3])
                    ->setOperationAmount($data[4])
                    ->setOperationCurrency($data[5]);

                $this->info($service->calculate($dto));
            }
            fclose($handle);

            return 1;
        }

        return 0;
    }
}
