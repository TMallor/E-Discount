<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ItemsController extends AbstractController
{
    #[Route('/items', name: 'items')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $articles = [];

        return $this->render('items/items.html.twig', [
            'articles' => $articles
        ]);
    }
} 