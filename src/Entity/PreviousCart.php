<?php

namespace App\Entity;

use App\Repository\PreviousCartRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;
use Symfony\Bridge\Doctrine\Types\UlidType;

#[ORM\Entity(repositoryClass: PreviousCartRepository::class)]
class PreviousCart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: UlidType::NAME)]
    private ?Ulid $user_id = null;

    #[ORM\Column]
    private ?int $article_id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $purchased_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?Ulid
    {
        return $this->user_id;
    }

    public function setUserId(Ulid $user_id): static
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getArticleId(): ?int
    {
        return $this->article_id;
    }

    public function setArticleId(int $article_id): static
    {
        $this->article_id = $article_id;
        return $this;
    }

    public function getPurchasedAt(): ?\DateTimeImmutable
    {
        return $this->purchased_at;
    }

    public function setPurchasedAt(\DateTimeImmutable $purchased_at): static
    {
        $this->purchased_at = $purchased_at;
        return $this;
    }
} 