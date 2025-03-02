<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Form\AdditemType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Cart;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function dashboard(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();
        $articles = $entityManager->getRepository(Article::class)->findAll();
        
        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'articles' => $articles
        ]);
    }

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

    #[Route('/articles', name: 'admin_articles')]
    public function articles(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findAll();
        
        return $this->render('admin/articles.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/articles/{id}/edit', name: 'admin_article_edit')]
    public function editArticle(Article $article, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdditemType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Article modifié avec succès');
            return $this->redirectToRoute('admin_articles');
        }

        return $this->render('admin/edit_article.html.twig', [
            'form' => $form->createView(),
            'article' => $article
        ]);
    }

    #[Route('/articles/{id}/delete', name: 'admin_article_delete', methods: ['POST'])]
    public function deleteArticle(Article $article, EntityManagerInterface $entityManager, Request $request): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }

        $entityManager->remove($article);
        $entityManager->flush();

        $this->addFlash('success', 'Article supprimé avec succès');
        return $this->redirectToRoute('admin_articles');
    }
} 