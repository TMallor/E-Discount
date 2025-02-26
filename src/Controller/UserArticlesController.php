<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Article;
use Symfony\Component\Uid\Ulid;
use App\Entity\Stock;
use App\Form\AdditemType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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

    #[Route('/profile/articles/{id}/edit', name: 'app_user_articles_edit')]
    public function edit(Article $article, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si l'utilisateur est le propriétaire de l'article
        if ($article->getAuthorId() !== $this->getUser()->getId()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier cet article.');
        }

        $form = $this->createForm(AdditemType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'upload de la nouvelle image si elle existe
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                    // Supprimer l'ancienne image si elle existe
                    if ($article->getImage()) {
                        $oldImagePath = $this->getParameter('images_directory').'/'.$article->getImage();
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                    $article->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image');
                }
            }

            // Mettre à jour le stock
            $stock = $entityManager->getRepository(Stock::class)->findOneBy(['article_id' => $article->getId()]);
            if ($stock) {
                $stock->setQuantity((string) $form->get('quantity')->getData());
            }

            $entityManager->flush();
            $this->addFlash('success', 'Article modifié avec succès');
            return $this->redirectToRoute('app_user_articles');
        }

        // Récupérer le stock pour pré-remplir le champ quantité
        $stock = $entityManager->getRepository(Stock::class)->findOneBy(['article_id' => $article->getId()]);
        if ($stock) {
            $form->get('quantity')->setData($stock->getQuantity());
        }

        return $this->render('user_articles/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article
        ]);
    }

    #[Route('/profile/articles/{id}/delete', name: 'app_user_articles_delete', methods: ['POST'])]
    public function delete(Article $article, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Vérifier le token CSRF
        if (!$this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }

        // Vérifier si l'utilisateur est le propriétaire de l'article
        if ($article->getAuthorId() !== $this->getUser()->getId()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer cet article.');
        }

        // Supprimer l'image associée
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
        $entityManager->flush();

        $this->addFlash('success', 'Article supprimé avec succès');
        return $this->redirectToRoute('app_user_articles');
    }
} 