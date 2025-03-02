<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Article;
use App\Entity\Stock;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\HttpFoundation\Request;

class CartController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/cart', name: 'cart')]
    #[IsGranted('ROLE_USER', message: 'Veuillez vous connecter pour accéder au panier')]
    public function index(): Response
    {
        $user = $this->getUser();
        $userId = (string) $user->getId();
        
        $cartItems = $this->entityManager->getRepository(Cart::class)
            ->findBy(['user_id' => Ulid::fromBase32($userId)]);
        
        $articles = [];
        $total = 0;
        
        foreach ($cartItems as $cartItem) {
            $article = $this->entityManager->getRepository(Article::class)->find($cartItem->getArticleId());
            if ($article) {
                $articles[] = [
                    'article' => $article,
                    'quantity' => $cartItem->getQuantity()
                ];
                $total += $article->getPrice() * $cartItem->getQuantity();
            }
        }

        return $this->render('cart/cart.html.twig', [
            'cart_items' => $articles,
            'total' => $total
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add', methods: ['POST'])]
    #[IsGranted('ROLE_USER', message: 'Veuillez vous connecter pour ajouter au panier')]
    public function addToCart(Request $request, Article $article): Response
    {
        $stock = $this->entityManager->getRepository(Stock::class)->findOneBy(['article' => $article]);
        
        if (!$stock || !$stock->isAvailable()) {
            $this->addFlash('error', 'Cet article n\'est pas disponible');
            return $this->redirectToRoute('items');
        }

        $quantity = min((int)$request->request->get('quantity', 1), $stock->getQuantity());
        
        // Vérifier si l'article est déjà dans le panier
        $user = $this->getUser();
        $userId = (string) $user->getId();
        
        $cartItem = $this->entityManager->getRepository(Cart::class)->findOneBy([
            'user_id' => Ulid::fromBase32($userId),
            'article_id' => $article->getId()
        ]);

        if ($cartItem) {
            // Mettre à jour la quantité
            $newQuantity = min($cartItem->getQuantity() + $quantity, $stock->getQuantity());
            $cartItem->setQuantity($newQuantity);
        } else {
            // Créer un nouvel item
            $cartItem = new Cart();
            $cartItem->setUserId($userId);
            $cartItem->setArticleId($article->getId());
            $cartItem->setQuantity($quantity);
            $this->entityManager->persist($cartItem);
        }

        // Mise à jour du stock
        $stock->setQuantity($stock->getQuantity() - $quantity);
        
        $this->entityManager->flush();
        
        $this->addFlash('success', 'Article ajouté au panier');
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    #[IsGranted('ROLE_USER', message: 'Veuillez vous connecter pour modifier le panier')]
    public function remove(Article $article): Response
    {
        $user = $this->getUser();
        $userId = (string) $user->getId();
        
        $cartItem = $this->entityManager->getRepository(Cart::class)->findOneBy([
            'user_id' => Ulid::fromBase32($userId),
            'article_id' => $article->getId()
        ]);
        
        if ($cartItem) {
            // Restituer le stock
            $stock = $article->getStock();
            if ($stock) {
                $stock->setQuantity($stock->getQuantity() + $cartItem->getQuantity());
            }
            
            $this->entityManager->remove($cartItem);
            $this->entityManager->flush();
            
            $this->addFlash('success', 'Article retiré du panier');
        }
        
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/update/{id}', name: 'cart_update_quantity', methods: ['POST'])]
    public function updateQuantity(Article $article, Request $request): Response
    {
        $user = $this->getUser();
        $userId = (string) $user->getId();
        
        $cartItem = $this->entityManager->getRepository(Cart::class)->findOneBy([
            'user_id' => Ulid::fromBase32($userId),
            'article_id' => $article->getId()
        ]);
        
        if ($cartItem) {
            $stock = $article->getStock();
            if ($stock) {
                $newQuantity = (int) $request->request->get('quantity', 1);
                $oldQuantity = $cartItem->getQuantity();
                
                // Calculer le stock total disponible (stock actuel + quantité dans le panier)
                $totalAvailableStock = $stock->getQuantity() + $oldQuantity;
                
                // Vérifier si la nouvelle quantité est possible
                if ($newQuantity <= $totalAvailableStock && $newQuantity > 0) {
                    // Ajuster le stock
                    $stock->setQuantity($totalAvailableStock - $newQuantity);
                    $cartItem->setQuantity($newQuantity);
                    
                    $this->entityManager->flush();
                    $this->addFlash('success', 'Quantité mise à jour');
                } else {
                    $this->addFlash('error', 'Quantité non valide');
                }
            }
        }
        
        return $this->redirectToRoute('cart');
    }
} 