<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Image;

class ProfileController extends AbstractController {
    /**
     * @Route("/profile/{id<\d+>}", name="profile")
     */
    public function profile($id, User $user, Request $request, SluggerInterface $slugger, UserPasswordEncoderInterface $encoder, PostRepository $postRepository, UserRepository $userRepository) {

        //$form=$this->createForm(RegistrationType::class,$user)->add('bio');
        $manager = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createBuilder(FormType::class, $user)
            ->add('nom', TextType::class, [
                'label' => 'nom',
                'attr' => [
                    'placeholder' => "nom"
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'prenom',
                'attr' => [
                    'placeholder' => "prenom"
                ]
            ])
            ->add('ProfilePicture', FileType::class, [
                'label' => 'Photo de profile',
                'constraints' => [
                    new Image()
                ],

                // unmapped means that this field is not associated to any entity property
                'mapped' => false
            ])
            ->add('Enregistrer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
            ->add('bio')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && (!$form->isValid())) {
            $this->addFlash('danger', 'verifier les donneés entreés');
            //dd($user);

        }
        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('ProfilePicture')->getData();

            //profile pic traitement
            // so the pic file must be processed only when a file is uploaded
            if ($picture) {
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();

                // Move the file to the directory where profile pictures are stored
                try {
                    $picture->move(
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


            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Vos Données sont modifiées');
        }
        $posts = $postRepository->findBy([
            'user' => $user
        ]);

        $followed=$user;

        return $this->render('profile/profile.html.twig', [
            'user' => $user,
            'profile_pictures_path' => $this->getParameter('profile_pictures_path'),
            'form' => $form->createView(),
            'posts' => $posts,
            'following' => $this->getUser()->getSubscribedTo()->contains($followed)
        ]);
    }

    /**
     * @Route("/subscribe/{followed_id<\d+>}",name="subscribe")
     */
    public function subscribe( $followed_id, UserRepository $userRepository, EntityManagerInterface $entityManager) {
        $followed = $userRepository->find($followed_id);
        $following = $this->getUser();
        if ($followed->getSubscribers()->contains($following)) {
            $followed->removeSubscriber($following);

        } else {
            $followed->addSubscriber($following);
        }
        $entityManager->persist($followed);
        $entityManager->flush();

        return $this->json([
            'following' => $following->getSubscribedTo()->contains($followed),
            'nombre_followers' => $followed->getSubscribers()->count()
        ], 200);


    }
}
