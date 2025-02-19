<?php
namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{
    /**
    * @Route("/additem", name="add_item")
    */
    public function addItem(Request $request): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $file = $form->get('file')->getData();

            if ($file)
            {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid().'.'.$file->guessExtension();

                // Déplacer le fichier vers le répertoire souhaité
                try {
                $file->move(
                $this->getParameter('uploads_directory'),
                $newFilename
                );
                } catch (FileException $e)
                    {
                // Gérer exception si nécessaire
                    }

                $item->setImageFilename($newFilename);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('item_list');
        }

    return $this->render('item/additem.html.twig', [
    'form' => $form->createView(),
    ]);
    }
}
?>