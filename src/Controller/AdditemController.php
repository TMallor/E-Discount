<?php
namespace App\Controller;

use App\Entity\Article;
use App\Form\AdditemType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final class AdditemController extends AbstractController
{
    #[Route('/additem', name: 'additem')]
    public function addItem(Request $request ,LoggerInterface $logger): Response
    {
        $item = new Article();
        $form = $this->createForm(AdditemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $slugger = new AsciiSlugger();
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $logger->error('Erreur lors du téléchargement de l\'image : ' . $e->getMessage());
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image.');
                    return $this->render('article/add.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }

                $item->setImage($newFilename);
            }

            $item->setPublicationDate((new \DateTime())->format('Y-m-d H:i:s'));
            $item->setAuthorId($this->getUser()->getId());

            $entityManager = $this->container->get('doctrine')->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            $this->addFlash('success', 'Votre article a été publié avec succès!');
            return $this->redirectToRoute('items');
        }

        return $this->render('additem/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
