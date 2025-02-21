<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    #[IsGranted('ROLE_USER', message: 'Veuillez vous connecter pour accéder au panier')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        
        // Convertir l'ID en chaîne
        $userId = (string) $user->getId();
        
        // Récupérer les articles du panier de l'utilisateur
        $cartItems = $entityManager->getRepository(Cart::class)->findBy(['user_id' => $userId]);
        
        // Récupérer les détails des articles
        $articles = [];
        $total = 0;
        
        foreach ($cartItems as $cartItem) {
            $article = $entityManager->getRepository(Article::class)->find($cartItem->getArticleId());
            if ($article) {
                $articles[] = [
                    'article' => $article,
                    'quantity' => 1 // À remplacer par la vraie quantité quand implémentée
                ];
                $total += $article->getPrice();
            }
        }

        return $this->render('cart/cart.html.twig', [
            'cart_items' => $articles,
            'total' => $total
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    #[IsGranted('ROLE_USER', message: 'Veuillez vous connecter pour ajouter au panier')]
    public function add(Article $article, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $userId = (string) $user->getId();
        
        // Vérifier si l'article est déjà dans le panier
        $existingItem = $entityManager->getRepository(Cart::class)->findOneBy([
            'user_id' => $userId,
            'article_id' => $article->getId()
        ]);
        
        if (!$existingItem) {
            $cartItem = new Cart();
            $cartItem->setUserId($userId);
            $cartItem->setArticleId($article->getId());
            
            $entityManager->persist($cartItem);
            $entityManager->flush();
            
            $this->addFlash('success', 'Article ajouté au panier');
        } else {
            $this->addFlash('info', 'Cet article est déjà dans votre panier');
        }
        
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    #[IsGranted('ROLE_USER', message: 'Veuillez vous connecter pour modifier le panier')]
    public function remove(Article $article, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $userId = (string) $user->getId();
        
        $cartItem = $entityManager->getRepository(Cart::class)->findOneBy([
            'user_id' => $userId,
            'article_id' => $article->getId()
        ]);
        
        if ($cartItem) {
            $entityManager->remove($cartItem);
            $entityManager->flush();
            
            $this->addFlash('success', 'Article retiré du panier');
        }
        
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/update/{id}/{quantity}', name: 'cart_update_quantity')]
    #[IsGranted('ROLE_USER', message: 'Veuillez vous connecter pour modifier le panier')]
    public function updateQuantity(Article $article, int $quantity, EntityManagerInterface $entityManager): Response
    {
        // À implémenter quand la quantité sera ajoutée à l'entité Cart
        return $this->redirectToRoute('cart');
    }
} 