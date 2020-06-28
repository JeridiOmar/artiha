<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class IndividualPostController
 * @package App\Controller
 * @Route("/post")
 */
class IndividualPostController extends AbstractController
{

    /**
     * @Route("/{id<\d+>}", name="individual_post")
     */
    public function index($id, PostRepository $postRepository, CommentRepository $commentRepository)
    {
        $post = $postRepository->findPostById($id);

        if (!$post) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        $comments = $commentRepository->getCommentsForPost($post->getId());

        return $this->render('individual_post/index.html.twig', array(
            'post'      => $post,
            'comments'  => $comments
        ));


    }

    /**
     * @Route("/comment/{userId<\d+>}_{postId<\d+>}", name="commentCreation")
     */
    public function comment($userId, $postId ,Request $request, Comment $comment, PostRepository $postRepository, CommentRepository $commentRepository, UserRepository $userRepository)
    {
        $manager = $this->getDoctrine()->getManager();
        $form = $this->get('form.factory')->createBuilder(FormType::class, $comment)
        ->add('content', TextType::class, [
        'label' => 'content',
        'attr' => [
            'placeholder' => "write your comment here",
            'rows'=>"3",
            'classe'=>"form-control"
        ]
    ])
            ->getForm();
        $form->handleRequest($request);

        $post = $postRepository->findPostById($postId);
        $user = $userRepository->findUserById($userId);
        $comment->setUser($user);
        $comment->setPost($post);
        $comment->setCreatedAt(new \DateTime());

        $manager->persist($comment);
        $manager->flush();
        $post = $postRepository->findPostById($postId);

        if (!$post) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        $comments = $commentRepository->getCommentsForPost($post->getId());

        return $this->render('individual_post/index.html.twig', array(
            'post'      => $post,
            'comments'  => $comments
        ));


    }

//    /**
//     * @Route("/like{idPost<\d+>}{idUser<\d+>}", name="individual_post")
//     */
//    public function index($id, PostRepository $postRepository)
//    {
//        $post = $postRepository->findPostById($id);
//        return $this->render('individual_post/index.html.twig', [
//            'post' => $post,
//        ]);
//    }
}
