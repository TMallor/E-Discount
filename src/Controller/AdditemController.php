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

            if (!$imageFile) {
                $this->addFlash('error', 'L\'image est obligatoire');
                return $this->redirectToRoute('additem');
            }

            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $slugger = new AsciiSlugger();
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
                
                $item->setImage($newFilename);
                $item->setAuthorId($this->getUser()->getId());
                $item->setPublicationDate(date('Y-m-d H:i:s'));

                // Création du stock
                $stock = new Stock();
                $stock->setQuantity((int) $form->get('quantity')->getData());
                $stock->setArticle($item);
                $item->setStock($stock);

                $this->entityManager->persist($item);
                $this->entityManager->persist($stock);
                $this->entityManager->flush();

                $this->addFlash('success', 'Article ajouté avec succès');
                return $this->redirectToRoute('app_user_articles');
            } catch (FileException $e) {
                $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image');
                return $this->redirectToRoute('additem');
            }
        }

        return $this->render('additem/additem.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
