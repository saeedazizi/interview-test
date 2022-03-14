<?php

namespace App\DTO;

class OperationDto
{
    private string $date;

    private int $userIdentifier;

    private string $userType;

    private string $operationType;

    private string $operationAmount;

    private string $operationCurrency;

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate($date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUserIdentifier(): int
    {
        return $this->userIdentifier;
    }

    public function setUserIdentifier(int $userIdentifier): self
    {
        $this->userIdentifier = $userIdentifier;

        return $this;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    public function setUserType(string $userType): self
    {
        $this->userType = $userType;

        return $this;
    }

    public function getOperationType(): string
    {
        return $this->operationType;
    }

    public function setOperationType(string $operationType): self
    {
        $this->operationType = $operationType;

        return $this;
    }

    public function getOperationAmount(): string
    {
        return $this->operationAmount;
    }

    public function setOperationAmount(string $operationAmount): self
    {
        $this->operationAmount = $operationAmount;

        return $this;
    }

    public function getOperationCurrency(): string
    {
        return $this->operationCurrency;
    }

    public function setOperationCurrency(string $operationCurrency): self
    {
        $this->operationCurrency = $operationCurrency;

        return $this;
    }
}
