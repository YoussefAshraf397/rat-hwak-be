<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;


#[Entity]
#[Table('payments')]
#[UniqueConstraint(columns: ['transaction_id'])]
class Payment
{
    #[Id]
    #[GeneratedValue('AUTO')]
    #[Column]
    private int $id;

    #[Column(name: 'error_code', nullable: true)]
    private ?int $errorCode;

    #[Column(name: 'transaction_id', nullable: true)]
    private ?string $transactionId;

    #[Column(name: 'amount', nullable: true)]
    private int $amount;

    #[Column(name: "created_at", type: "datetime",  options:["default" => 'CURRENT_TIMESTAMP'])]
    private ?\DateTime $createdAt;

    public function getAmount(): int
    {
        return $this->amount;
    }


    public function setAmount(int $amount): Payment
    {
        $this->amount = $amount;
        return $this;
    }



    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Payment
    {
        $this->id = $id;
        return $this;
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function setErrorCode(int $errorCode): Payment
    {
        $this->errorCode = $errorCode;
        return $this;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function setTransactionId(string $transactionId): Payment
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): Payment
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
