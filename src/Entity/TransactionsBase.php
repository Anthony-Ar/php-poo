<?php
namespace App\Entity;

use App\Repository\Database;

class TransactionsBase extends Database
{
    private int $id;
    private string $date;
    private int $accountId;
    private int $quantity;
    private int $price;

    public function __construct()
    {
        parent::__construct('transactions');
    }

    public function getId(): int 
    {
        return $this->id;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $value): void
    {
        $this->date = $value;
    }

    public function getAccountId(): int
    {
        return $this->accountId;
    }

    public function setAccountId(int $value): void
    {
        $this->accountId = $value;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $value): void
    {
        $this->accountId = $value;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $value): void
    {
        $this->price = $value;
    }
}