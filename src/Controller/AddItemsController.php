<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AddItemsController extends AbstractController
{
    #[Route('/additems', name: 'additems')]
    public function index(): Response
    {
        return $this->render('additems/additems.html.twig');
    }
} 