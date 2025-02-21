<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;


final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Supprimer le doublon Nike
        $articles = $entityManager->getRepository(Article::class)
            ->findBy(['name' => 'Nike Air Max 90'], ['id' => 'ASC']);

        if (count($articles) > 1) {
            // Garder le premier, supprimer le deuxième
            $entityManager->remove($articles[1]);
            $entityManager->flush();
        }

        // Récupérer les 4 premiers articles
        $articles = $entityManager->getRepository(Article::class)
            ->findBy([], ['id' => 'ASC'], 4);

        return $this->render('home/home.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/button-action', name: 'button_action', methods: ['GET', 'POST'])]
    public function buttonAction(Request $request): Response
    {
        $action = $request->query->get('action') ?? $request->request->get('action');

        if ($action === 'action1') {
            // Gérer l'action 1
            return new Response('Action 1 exécutée !');
        } elseif ($action === 'action2') {
            // Gérer l'action 2
            return new Response('Action 2 exécutée !');
        }

        return new Response('Aucune action spécifiée.');
    }
}
