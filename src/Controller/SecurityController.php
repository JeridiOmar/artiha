<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class SecurityController extends AbstractController {
    /**
     * @Route("/registration", name="registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger, UserPasswordEncoderInterface $encoder) {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && (!$form->isValid())) {
            $this->addFlash('danger', 'verifier les donneés entreés</a>');

        }
        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('ProfilePicture')->getData();

            //profile pic traitement
            // so the pic file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where profile pictures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('profile_pictures_path'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'profile pic ' property to store the image file name
                // instead of its contents
                $user->setProfilePicture($newFilename);
            }
            //fin traitement du pic
            //encodage du mot de pase
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);


            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Vous etes maintenant inscrit <a href="#" class="alert-link">Connectez-vous!</a>');
        }
        return $this->render('security/registration.html.twig', [
            'form_registration' => $form->createView()]);
    }


}
