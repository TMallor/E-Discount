<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Article;
use Symfony\Component\Uid\Ulid;

#[IsGranted('ROLE_USER')]
class UserArticlesController extends AbstractController
{
    #[Route('/profile/articles', name: 'app_user_articles')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $userId = $user->getId(); // Récupération de l'ID de l'utilisateur connecté
        
        // Utilisation du QueryBuilder pour récupérer uniquement les articles de l'utilisateur
        $articles = $entityManager->createQueryBuilder()
            ->select('a')
            ->from(Article::class, 'a')
            ->where('a.author_id = :userId')
            ->setParameter('userId', Ulid::fromBase32($userId)->toBase32()) // Conversion en ULID
            ->getQuery()
            ->getResult();

        return $this->render('user_articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }
} 