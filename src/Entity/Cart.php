<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;
use Symfony\Bridge\Doctrine\Types\UlidType;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: UlidType::NAME)]
    private ?Ulid $user_id = null;

    #[ORM\Column]
    private ?int $article_id = null;

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
}
