<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($request->isMethod('POST')) {
            $user->setFirstName($request->request->get('firstName'))
                ->setLastName($request->request->get('lastName'))
                ->setEmail($request->request->get('email'))
                ->setPhone($request->request->get('phone'))
                ->setStreet($request->request->get('street'))
                ->setCity($request->request->get('city'))
                ->setPostalCode($request->request->get('postalCode'))
                ->setCountry($request->request->get('country'));
            
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès');
            
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/profile.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile/address', name: 'app_profile_address', methods: ['POST'])]
    public function updateAddress(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        
        $user->setStreet($request->request->get('street'));
        $user->setCity($request->request->get('city'));
        $user->setPostalCode($request->request->get('postalCode'));
        $user->setCountry($request->request->get('country'));
        
        $entityManager->persist($user);
        $entityManager->flush();
        
        $this->addFlash('success', 'Adresse mise à jour avec succès');
        
        return $this->redirectToRoute('app_profile');
    }

    #[Route('/profile/update-picture', name: 'app_profile_update_picture', methods: ['POST'])]
    public function updateProfilePicture(
        Request $request, 
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        
        /** @var UploadedFile $profilePicture */
        $profilePicture = $request->files->get('profile_picture');

        if ($profilePicture) {
            $originalFilename = pathinfo($profilePicture->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$profilePicture->guessExtension();

            try {
                $profilePicture->move(
                    $this->getParameter('kernel.project_dir') . '/public/image',
                    $newFilename
                );
                
                // Supprimer l'ancienne photo si ce n'est pas la photo par défaut
                if ($user->getProfilePicture() !== 'ethan.jpeg') {
                    $oldFile = $this->getParameter('kernel.project_dir') . '/public/image/' . $user->getProfilePicture();
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
                
                $user->setProfilePicture($newFilename);
                $entityManager->flush();
                
                $this->addFlash('success', 'Photo de profil mise à jour avec succès');
            } catch (FileException $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'upload de l\'image');
            }
        }
        
        return $this->redirectToRoute('app_profile');
    }
} 