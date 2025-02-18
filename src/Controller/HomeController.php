<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;


final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
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
