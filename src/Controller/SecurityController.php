<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\Post;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use http\Env\Response;
use Psr\Container\ContainerInterface;
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
        if($this->isGranted("IS_AUTHENTICATED_FULLY"))
        {
            return $this->redirectToRoute("welcomePage");
        }
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && (!$form->isValid())) {
            $this->addFlash('danger', 'verifier les donneés entreés</a>');

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
                $user->setIsFixture(false);
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
    /**
     * @Route("/usernames/{username}", name="usernameTester")
     */
    public function usernameTester($username,UserRepository $repository){
        if($repository->findOneBy([
            'username'=>$username
        ])){
            return $this->json(['isUnique'=>'no'],200);
        }
        return $this->json(['isUnique'=>'yes'],200);
    }

    /**
     * @Route("/fixtures", name="fixtures")
     */
    public function fix(UserRepository $userRepository,PostRepository $postRepository,EntityManagerInterface $entityManager) {
        $faker=Factory::create();

        for ($i = 0; $i < 10; $i++){
            $like=new Like();

            $user=$userRepository->find((int)rand(60,100));

            $post=$postRepository->find(rand(60,100));
            dump($user);
            $like->setUser($user);
            $like->setPost($post);
            $entityManager->persist($like);

        }
        for ($i = 0; $i < 10; $i++){
            $comment=new Comment();

            $user=$userRepository->find(rand(60,100));

            $post=$postRepository->find(rand(60,100));
            $comment->setUser($user);
            //dump($user);
            $comment->setPost($post);
            $comment->setContent($faker->text(55));

            $entityManager->persist($comment);

        }
        $entityManager->flush();
        return $this->redirectToRoute('app_login');
    }


}
