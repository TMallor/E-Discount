<?php

namespace App\Controller;

use App\Entity\Article;
use App\Enum\ArticleCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemsController extends AbstractController
{
    #[Route('/items', name: 'items')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $selectedCategory = $request->query->get('category');
        $categories = ArticleCategory::cases();

        $queryBuilder = $entityManager->createQueryBuilder()
            ->select('a')
            ->from(Article::class, 'a');

        if ($selectedCategory) {
            $queryBuilder->where('a.category = :category')
                ->setParameter('category', $selectedCategory);
        }

        $articles = $queryBuilder->getQuery()->getResult();

        // Grouper les articles par catégorie
        $articlesByCategory = [];
        foreach ($articles as $article) {
            $category = $article->getCategory();
            if (!isset($articlesByCategory[$category])) {
                $articlesByCategory[$category] = [];
            }
            $articlesByCategory[$category][] = $article;
        }

        return $this->render('items/items.html.twig', [
            'articlesByCategory' => $articlesByCategory,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory
        ]);
    }
} 