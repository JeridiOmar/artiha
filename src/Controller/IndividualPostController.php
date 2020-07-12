<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\RegistrationType;
use App\Repository\CommentRepository;
use App\Repository\LikeRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use MongoDB\Driver\Manager;
use PhpParser\Node\Expr\Cast\Object_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
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
     * @param $id
     * @param PostRepository $postRepository
     * @param CommentRepository $commentRepository
     * @param $userRepository
     * @return Response
     */
    public function index(Request $request,$id, PostRepository $postRepository, CommentRepository $commentRepository,UserRepository $userRepository,EntityManagerInterface $entityManager)
    {
        $post = $postRepository->findPostById($id);
        $likes = $post->getLikes();
        $comment=new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $comment->setCreatedAt(new \DateTime());
            $comment->setPost($post);
            $cnct=$userRepository->findOneBy(['username'=>$this->getUser()->getUsername()]);
            $comment->setUser($cnct);
            
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Commentaire ajoutée');
        }

        if (!$post) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        $comments = $commentRepository->getCommentsForPost($post->getId());

        return $this->render('individual_post/index.html.twig', array(
            'post'      => $post,
            'comments'  => $comments,
        'likes'=>$likes,
            'form'=>$form->createView()

        ));

    }

    /**
     * @Route("/{id<\d+>}/like", name="post_like")
     */
    public function like($id, EntityManagerInterface $manager, PostRepository $postRepository, LikeRepository $likeRepository) : Response{
        $post = $postRepository->findPostById($id);
        $user = $this->getUser();
//        if (!$user) return $this->json(['code'=>403 ,
//            'error'=>'you\'re not connected']);
        if($post->isLikedByUser($user)){
            $like = $likeRepository->findOneBy([
                'post'=>$post,
                'user'=>$user
            ]);
            $post->removeLike($like);
            $user->removeLike($like);
            $manager->remove($like);
            $manager->persist($user);
            $manager->persist($post);
            $manager->flush();


        }
        else{
        $like = new Like();
        $like->setPost($post)
            ->setUser($user);
        $user->addLike($like);
        $post->addLike($like);
            $manager->persist($user);
            $manager->persist($post);
        $manager->persist($like);
        $manager->flush();

}
        return $this->json(['liked'=>$post->isLikedbyUser($user),
            'nbLike'=>$likeRepository->count(['post'=>$post]),
        ],200);


    }

    /**
     * @Route("/{id<\d+>}/delete", name="delete")
     */
    public function delete($id, EntityManagerInterface $manager, PostRepository $postRepository) {
        $post = $postRepository->findPostById($id);
        $user = $this->getUser();
        if (( $user->getId() == $post->getUser()->getId())||($user->getIsAdmin())){
            $manager->remove($post);
            $manager->flush();
        }

        return $this->redirectToRoute("post");

    }

//
//    /**
//     * @Route("/comment/{id<\d+>}", name="commentCreation")
//     */
//       public function comment($id,Request $request ,EntityManagerInterface $manager, PostRepository $postRepository, CommentRepository $commentRepository) : Response
//       {
//
//           $post = $postRepository->findPostById($id);
//           $user = $this->getUser();
////        if (!$user) return $this->json(['code'=>403 ,
////            'error'=>'you\'re not connected']);
//
//           $comment = new Comment();
////           $form = $this->get('form.factory')->createBuilder(FormType::class, $comment)
////           ->add('content', TextType::class, [
////               'label' => 'nom',
////               'attr' => [
////                   'placeholder' => "write your comment here"
////               ]
////           ])->getForm();
////           $form->handleRequest($request);
//
//           $comment->setPost($post)
//               ->setUser($user);
//           $user->addComment($comment);
//           $post->addComment($comment);
//           $manager->persist($user);
//           $manager->persist($post);
//           $manager->persist($comment);
//           $manager->flush();
//
//
//           return $this->json(['commented' => $post->hasCommented($comment),
//
//               'commentContent' => $comment.getContent(),
//
//               'nbComments'=>$commentRepository->count(['post'=>$post])
//               ], 200);
//
//
//
//       }
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

