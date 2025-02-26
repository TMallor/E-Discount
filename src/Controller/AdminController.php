<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use App\Entity\Cart;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/users', name: 'admin_users')]
    public function users(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();
        
        return $this->render('admin/users.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/users/{id}/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function deleteUser(User $user, EntityManagerInterface $entityManager, Request $request): Response
    {
        if (!$this->isCsrfTokenValid('delete-user'.$user->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }

        // Empêcher la suppression d'un admin
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $this->addFlash('error', 'Impossible de supprimer un administrateur');
            return $this->redirectToRoute('admin_users');
        }

        // Supprimer l'image de profil
        if ($user->getProfilePicture() && $user->getProfilePicture() !== 'ethan.jpeg') {
            $imagePath = $this->getParameter('profile_pictures_directory').'/'.$user->getProfilePicture();
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Supprimer les articles de l'utilisateur
        $articles = $entityManager->getRepository(Article::class)->findBy(['author_id' => $user->getId()]);
        foreach ($articles as $article) {
            if ($article->getImage()) {
                $imagePath = $this->getParameter('images_directory').'/'.$article->getImage();
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $entityManager->remove($article);
        }

        // Supprimer les entrées du panier
        $cartItems = $entityManager->getRepository(Cart::class)->findBy(['user_id' => $user->getId()]);
        foreach ($cartItems as $cartItem) {
            $entityManager->remove($cartItem);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur supprimé avec succès');
        return $this->redirectToRoute('admin_users');
    }
} 