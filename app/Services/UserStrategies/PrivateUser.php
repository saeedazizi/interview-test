<?php

namespace App\Services\UserStrategies;

use App\DTO\OperationDto;

class PrivateUser extends AbstractStrategy
{
    private static array $currencyRates = [];

    public function isEligible(OperationDto $dto): bool
    {
        return $dto->getUserType() === 'private';
    }

    public function supply(OperationDto $dto): string
    {
        $commission = 0;

        if ($this->isMoreThanThreeTimesInWeek($dto)) {
            $commission = $dto->getOperationAmount() * 0.003;
        } elseif ($this->isExceeded($dto)) {
            $commission = $this->getExceededAmount($dto) * 0.003;
        }

        $this->setHistory($dto->getUserIdentifier(), $dto->getDate(), $this->getExchangedAmount($dto));

        return $this->formatOutput($this->roundUp($commission), $dto);
    }

    private function getDayOfWeek(string $date): string
    {
        return date("l", strtotime($date));
    }

    private function getCurrencyRates(): array
    {
        if (empty(self::$currencyRates)) {
            $cURLConnection = curl_init();

            curl_setopt(
                $cURLConnection,
                CURLOPT_URL,
                'https://developers.paysera.com/tasks/api/currency-exchange-rates'
            );
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

            $currencyRates = curl_exec($cURLConnection);
            curl_close($cURLConnection);

            self::$currencyRates = json_decode($currencyRates, true)['rates'];
        }

        return self::$currencyRates;
    }

    private function getCurrencyRate(string $currency): float
    {
        return $this->getCurrencyRates()[$currency] ?? 1;
    }

    private function isExceeded(OperationDto $dto): bool
    {
        $userHistory = $this->getHistory($dto->getUserIdentifier());
        $thisWeekDays = $this->generateThisWeekDays($dto->getDate());
        $baseAmount = $this->getExchangedAmount($dto);

        foreach ($userHistory as $date => $amount) {
            if ($date >= $thisWeekDays['start'] && $date <= $thisWeekDays['end']) {
                $baseAmount += $amount;
            }
        }

        return $baseAmount > 1000;
    }

    private function getExceededAmount(OperationDto $dto): float
    {
        $userHistory = $this->getHistory($dto->getUserIdentifier());
        $thisWeekDays = $this->generateThisWeekDays($dto->getDate());
        $baseAmount = 0;

        foreach ($userHistory as $date => $amount) {
            if ($date >= $thisWeekDays['start'] && $date <= $thisWeekDays['end']) {
                $baseAmount += $amount;
            }
        }

        $exchangedAmount = $this->getExchangedAmount($dto);
        $EURBaseExceeded = $baseAmount > 1000 ? $exchangedAmount : ($exchangedAmount + $baseAmount) - 1000;

        return $EURBaseExceeded * $this->getCurrencyRate($dto->getOperationCurrency());
    }

    private function getExchangedAmount(OperationDto $dto): float
    {
        return $dto->getOperationAmount() / $this->getCurrencyRate($dto->getOperationCurrency());
    }

    private function isMoreThanThreeTimesInWeek(OperationDto $dto): bool
    {
        $userHistory = $this->getHistory($dto->getUserIdentifier());
        $currentDate = $dto->getDate();

        $thisWeekDays = $this->generateThisWeekDays($currentDate);

        $count = 1; //I set to one because current transaction is in the range.
        foreach ($userHistory as $date => $amount) {
            if ($date >= $thisWeekDays['start'] && $date <= $thisWeekDays['end']) {
                $count++;
            }
        }

        if ($count > 3) {
            return true;
        }

        return false;
    }

    private function generateThisWeekDays(string $date): array
    {
        $dayWeek = $this->getDayOfWeek($date);
        $startDay = '';
        $endDay = '';

        switch ($dayWeek) {
            case 'Monday':
                $startDay = $date;
                $endDay = (new \DateTime($date))->add(date_interval_create_from_date_string('7 days'))->format('Y-m-d');
                break;
            case 'Tuesday':
                $startDay = (new \DateTime($date))->sub(date_interval_create_from_date_string('1 days'))->format(
                    'Y-m-d'
                );
                $endDay = (new \DateTime($date))->add(date_interval_create_from_date_string('6 days'))->format('Y-m-d');
                break;
            case 'Wednesday':
                $startDay = (new \DateTime($date))->sub(date_interval_create_from_date_string('2 days'))->format(
                    'Y-m-d'
                );
                $endDay = (new \DateTime($date))->add(date_interval_create_from_date_string('5 days'))->format('Y-m-d');
                break;
            case 'Thursday':
                $startDay = (new \DateTime($date))->sub(date_interval_create_from_date_string('3 days'))->format(
                    'Y-m-d'
                );
                $endDay = (new \DateTime($date))->add(date_interval_create_from_date_string('4 days'))->format('Y-m-d');
                break;
            case 'Friday':
                $startDay = (new \DateTime($date))->sub(date_interval_create_from_date_string('4 days'))->format(
                    'Y-m-d'
                );
                $endDay = (new \DateTime($date))->add(date_interval_create_from_date_string('3 days'))->format('Y-m-d');
                break;
            case 'Saturday':
                $startDay = (new \DateTime($date))->sub(date_interval_create_from_date_string('5 days'))->format(
                    'Y-m-d'
                );
                $endDay = (new \DateTime($date))->add(date_interval_create_from_date_string('2 days'))->format('Y-m-d');
                break;
            case 'Sunday':
                $startDay = (new \DateTime($date))->sub(date_interval_create_from_date_string('6 days'))->format(
                    'Y-m-d'
                );
                $endDay = $date;
                break;
        }

        return ['start' => $startDay, 'end' => $endDay];
    }
}
