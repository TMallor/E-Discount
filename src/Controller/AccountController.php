<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Article;
use App\Entity\Stock;
use App\Entity\Cart;

#[IsGranted('ROLE_USER')]
class AccountController extends AbstractController
{
    #[Route('/account/delete', name: 'app_account_delete', methods: ['POST'])]
    public function deleteAccount(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();
        
        if (!$this->isCsrfTokenValid('delete-account', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }

        // Supprimer l'image de profil
        if ($user->getProfilePicture() && $user->getProfilePicture() !== 'ethan.jpeg') {
            $imagePath = $this->getParameter('profile_pictures_directory').'/'.$user->getProfilePicture();
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Supprimer les images des articles de l'utilisateur
        $articles = $entityManager->getRepository(Article::class)->findBy(['author_id' => $user->getId()]);
        foreach ($articles as $article) {
            if ($article->getImage()) {
                $imagePath = $this->getParameter('images_directory').'/'.$article->getImage();
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            // Supprimer le stock associé
            $stock = $entityManager->getRepository(Stock::class)->findOneBy(['article_id' => $article->getId()]);
            if ($stock) {
                $entityManager->remove($stock);
            }
            
            $entityManager->remove($article);
        }

        // Supprimer les entrées du panier
        $cartItems = $entityManager->getRepository(Cart::class)->findBy(['user_id' => $user->getId()]);
        foreach ($cartItems as $cartItem) {
            $entityManager->remove($cartItem);
        }

        // Supprimer l'utilisateur
        $entityManager->remove($user);
        $entityManager->flush();

        // Déconnexion
        $this->container->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate();

        $this->addFlash('success', 'Votre compte a été supprimé avec succès');
        return $this->redirectToRoute('home');
    }
} 