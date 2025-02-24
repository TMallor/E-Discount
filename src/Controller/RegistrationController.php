<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        Security $security, 
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            /** @var UploadedFile $profilePicture */
            $profilePicture = $form->get('profilePicture')->getData();

            if (!$profilePicture) {
                $this->addFlash('error', 'La photo de profil est obligatoire');
                return $this->redirectToRoute('app_register');
            }

            $originalFilename = pathinfo($profilePicture->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$profilePicture->guessExtension();

            try {
                $profilePicture->move(
                    $this->getParameter('kernel.project_dir') . '/public/image',
                    $newFilename
                );
                $user->setProfilePicture($newFilename);
            } catch (FileException $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'upload de l\'image');
                return $this->redirectToRoute('app_register');
            }

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            
            // Initialiser la balance à 0
            $user->setBalance(0.0);

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $security->login($user, 'form_login', 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
