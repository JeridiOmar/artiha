<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\SearchForm;
use App\Home\SearchEntity;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController
 * @package App\Controller
 * @Route("/search")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/", name="search")
     */
    public function search(Request $request,TagRepository $tagRepository,PostRepository $postRepository,UserRepository $userRepository)
    {
        $search=new SearchEntity();
        $form= $this->createForm(SearchForm::class,$search);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
           if ($search->choice=="Users"){
               $users=$userRepository->findSearch($search);
               return$this->render('search/usersSearch.html.twig',[
                   'users'=>$users,
                   'tag'=>$search->motCle,
                   'type'=>'User'
               ]);
           }
           if ($search->choice=="Tags"){
               $posts=$postRepository->findByTagName($search);
               return$this->render('search/postSearch.html.twig',[
                   'posts'=>$posts,
                   'tag'=>$search->motCle,
                   'type'=>'TAG'
               ]);
           }  if ($search->choice=="Post Title"){
               $posts=$postRepository->findByPostTitle($search);
               return$this->render('search/postSearch.html.twig',[
                   'posts'=>$posts,
                   'tag'=>$search->motCle,
                   'type'=>'PostTitle'
               ]);
           }  if ($search->choice=="Description"){
               $posts=$postRepository->findDescriptipn($search);
               return$this->render('search/postSearch.html.twig',[
                   'posts'=>$posts,
                   'tag'=>$search->motCle,
                   'type'=>'Description'
               ]);
           }

        }
        return $this->render('search/search.html.twig', [
         'form'=>$form->createView()
        ]);
    }
///**
// * @Route("/user",name="userSearch")
// */
//public function userSearch(UserRepository){
//    $users=$userRepository->findSearch("s");
//    return$this->render('search/usersSearch.html.twig',[
//        'users'=>"users"
//    ]);
//
//}
}
