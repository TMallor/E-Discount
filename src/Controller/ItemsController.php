<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ItemsController extends AbstractController
{
    #[Route('/items', name: 'items')]
    public function index(): Response
    {
        return $this->render('items/items.html.twig');
    }
} 