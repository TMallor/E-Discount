<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'checkout')]
    #[IsGranted('ROLE_USER')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        
        // Récupérer les articles du panier
        $cartItems = $entityManager->getRepository(Cart::class)->findBy(['user_id' => $user->getId()]);
        
        // Récupérer les détails des articles
        $articles = [];
        $total = 0;
        
        foreach ($cartItems as $cartItem) {
            $article = $entityManager->getRepository(Article::class)->find($cartItem->getArticleId());
            if ($article) {
                $articles[] = [
                    'article' => $article,
                    'quantity' => 1
                ];
                $total += $article->getPrice();
            }
        }

        return $this->render('checkout/checkout.html.twig', [
            'cart_items' => $articles,
            'total' => $total,
            'user' => $user
        ]);
    }

    #[Route('/checkout/confirmation', name: 'checkout_confirmation')]
    public function confirmation(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        
        // Générer un numéro de commande unique
        $orderNumber = 'CMD-' . strtoupper(uniqid());
        
        // Récupérer les articles du panier
        $cartItems = $entityManager->getRepository(Cart::class)->findBy(['user_id' => $user->getId()]);
        
        // Récupérer les détails des articles
        $articles = [];
        $total = 0;
        
        foreach ($cartItems as $cartItem) {
            $article = $entityManager->getRepository(Article::class)->find($cartItem->getArticleId());
            if ($article) {
                $articles[] = [
                    'article' => $article,
                    'quantity' => 1
                ];
                $total += $article->getPrice();
            }
        }

        // Préparer l'adresse de livraison
        $shippingAddress = [
            'fullname' => $user->getFirstName() . ' ' . $user->getLastName(),
            'address' => $user->getStreet(),
            'postal' => $user->getPostalCode(),
            'city' => $user->getCity()
        ];

        return $this->render('checkout/confirmation.html.twig', [
            'order_number' => $orderNumber,
            'cart_items' => $articles,
            'total' => $total,
            'shipping_address' => $shippingAddress
        ]);
    }
} 