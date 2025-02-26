<?php
namespace App\Controller;

use App\Entity\Article;
use App\Entity\Stock;
use App\Form\AdditemType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final class AdditemController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/additem', name: 'additem')]
    public function addItem(Request $request): Response
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
                    $this->addFlash('success', 'Image uploadée dans : ' . $this->getParameter('images_directory') . '/' . $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image');
                }

                $item->setImage($newFilename);
            }

            $item->setPublicationDate((new \DateTime())->format('Y-m-d H:i:s'));
            $item->setAuthorId($this->getUser()->getId());

            // Persister d'abord l'article
            $this->entityManager->persist($item);
            $this->entityManager->flush();

            // Maintenant que l'article a un ID, créer l'enregistrement de stock
            $stock = new Stock();
            $stock->setArticleId($item->getId()); // L'ID est maintenant disponible
            $stock->setQuantity((string) $form->get('quantity')->getData());
            
            $this->entityManager->persist($stock);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_user_articles');
        }

        return $this->render('additem/additem.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
