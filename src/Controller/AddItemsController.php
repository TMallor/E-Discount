<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER', message: 'Veuillez vous connecter pour accéder à cette page')]
class AddItemsController extends AbstractController
{
    #[Route('/additems', name: 'additems')]
    public function index(): Response
    {
        return $this->render('items/additems.html.twig');
    }
} 