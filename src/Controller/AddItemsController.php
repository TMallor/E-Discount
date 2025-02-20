<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AddItemsController extends AbstractController
{
    #[Route('/additems', name: 'additems')]
    #[IsGranted('ROLE_USER', message: 'Veuillez vous connecter pour ajouter des articles')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('items/additems.html.twig');
    }
} 