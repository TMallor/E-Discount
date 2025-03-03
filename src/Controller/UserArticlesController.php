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
use Symfony\Component\String\Slugger\AsciiSlugger;

#[IsGranted('ROLE_USER')]
class UserArticlesController extends AbstractController
{
    #[Route('/profile/articles', name: 'app_user_articles')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        
        // Si c'est un admin, récupérer tous les articles
        if ($this->isGranted('ROLE_ADMIN')) {
            $articles = $entityManager->getRepository(Article::class)->findAll();
        } else {
            // Sinon, récupérer uniquement les articles de l'utilisateur
            $userId = $user->getId();
            $articles = $entityManager->createQueryBuilder()
                ->select('a')
                ->from(Article::class, 'a')
                ->where('a.author_id = :userId')
                ->setParameter('userId', Ulid::fromBase32($userId)->toBase32())
                ->getQuery()
                ->getResult();
        }

        return $this->render('user_articles/index.html.twig', [
            'articles' => $articles,
            'is_admin' => $this->isGranted('ROLE_ADMIN')
        ]);
    }

    #[Route('/profile/articles/{id}/edit', name: 'app_user_articles_edit')]
    public function edit(Article $article, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si l'utilisateur est l'auteur ou un admin
        if (!$this->isGranted('ROLE_ADMIN') && $article->getAuthorId() !== $this->getUser()->getId()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier cet article.');
        }

        $form = $this->createForm(AdditemType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer le téléchargement de la nouvelle image si elle existe
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                // Supprimer l'ancienne image
                $this->removeImage($article->getImage());

                // Enregistrer la nouvelle image
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $slugger = new AsciiSlugger();
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                    $article->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image');
                    return $this->redirectToRoute('app_user_articles_edit', ['id' => $article->getId()]);
                }
            }

            // Mettre à jour les autres champs de l'article
            $article->setName($form->get('name')->getData());
            $article->setCategory($form->get('category')->getData());
            $article->setMainfeatures($form->get('mainfeatures')->getData());
            $article->setDescription($form->get('description')->getData());
            $article->setPrice($form->get('price')->getData());

            // Mettre à jour le stock
            $stock = $entityManager->getRepository(Stock::class)->findOneBy(['article' => $article]);
            if ($stock) {
                $stock->setQuantity((int) $form->get('quantity')->getData());
            } else {
                // Créer un nouveau stock si nécessaire
                $stock = new Stock();
                $stock->setArticle($article);
                $stock->setQuantity((int) $form->get('quantity')->getData());
                $entityManager->persist($stock);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Article modifié avec succès');
            return $this->redirectToRoute('app_user_articles');
        }

        // Récupérer le stock pour pré-remplir le champ quantité
        $stock = $entityManager->getRepository(Stock::class)->findOneBy(['article' => $article]);
        if ($stock) {
            $form->get('quantity')->setData($stock->getQuantity());
        }

        return $this->render('user_articles/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article
        ]);
    }

    private function removeImage(?string $imageName): void
    {
        if ($imageName) {
            $imagePath = $this->getParameter('images_directory').'/'.$imageName;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    }

    #[Route('/profile/articles/{id}/delete', name: 'app_user_articles_delete', methods: ['POST'])]
    public function delete(Article $article, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Vérifier le token CSRF
        if (!$this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }

        // Vérifier si l'utilisateur est l'auteur ou un admin
        if (!$this->isGranted('ROLE_ADMIN') && $article->getAuthorId() !== $this->getUser()->getId()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer cet article.');
        }

        // Supprimer l'image associée
        $this->removeImage($article->getImage());

        // Supprimer le stock associé
        $stock = $entityManager->getRepository(Stock::class)->findOneBy(['article' => $article]);
        if ($stock) {
            $entityManager->remove($stock);
        }

        $entityManager->remove($article);
        $entityManager->flush();

        $this->addFlash('success', 'Article supprimé avec succès');
        return $this->redirectToRoute('app_user_articles');
    }
} 