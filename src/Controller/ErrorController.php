<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ErrorController extends AbstractController
{
    public function show(\Throwable $exception): Response
    {
        if ($exception instanceof NotFoundHttpException) {
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig', [], 
                new Response('', 404)
            );
        }

        // Pour les autres types d'erreurs
        return $this->render('bundles/TwigBundle/Exception/error.html.twig', [
            'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
        ], new Response('', Response::HTTP_INTERNAL_SERVER_ERROR));
    }
} 