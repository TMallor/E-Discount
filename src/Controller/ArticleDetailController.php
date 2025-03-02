<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleDetailController extends AbstractController
{
    #[Route('/article/{id}', name: 'article_detail')]
    public function detail(Article $article): Response
    {
        return $this->render('article/detail.html.twig', [
            'article' => $article
        ]);
    }
} 