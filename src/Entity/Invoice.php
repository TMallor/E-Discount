<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $user_id = null;

    #[ORM\Column(length: 255)]
    private ?string $transaction_date = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(length: 255)]
    private ?string $billing_adress = null;

    #[ORM\Column(length: 255)]
    private ?string $billing_city = null;

    #[ORM\Column(length: 255)]
    private ?string $billing_postal_code = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getTransactionDate(): ?string
    {
        return $this->transaction_date;
    }

    public function setTransactionDate(string $transaction_date): static
    {
        $this->transaction_date = $transaction_date;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getBillingAdress(): ?string
    {
        return $this->billing_adress;
    }

    public function setBillingAdress(string $billing_adress): static
    {
        $this->billing_adress = $billing_adress;

        return $this;
    }

    public function getBillingCity(): ?string
    {
        return $this->billing_city;
    }

    public function setBillingCity(string $billing_city): static
    {
        $this->billing_city = $billing_city;

        return $this;
    }

    public function getBillingPostalCode(): ?string
    {
        return $this->billing_postal_code;
    }

    public function setBillingPostalCode(string $billing_postal_code): static
    {
        $this->billing_postal_code = $billing_postal_code;

        return $this;
    }
}
