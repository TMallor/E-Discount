<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: Article::class, inversedBy: 'stock')]
    #[ORM\JoinColumn(name: "article_id", referencedColumnName: "id", nullable: false)]
    private ?Article $article = null;

    #[ORM\Column]
    private ?int $quantity = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;
        if ($article !== null && $article->getStock() !== $this) {
            $article->setStock($this);
        }
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function isAvailable(): bool
    {
        return $this->quantity > 0;
    }
}
