<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\FiltreForm;
use App\Form\SearchForm;
use App\Search\SearchEntity;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use App\Search\SearchHome;
use phpDocumentor\Reflection\DocBlock\Tag;
use phpDocumentor\Reflection\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    public function searchBar(){

    }
    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request,TagRepository $tagRepository,PostRepository $postRepository,UserRepository $userRepository)
    {
        $userSuggestions=$userRepository->findRandom();
        $search=new SearchEntity();
        $form= $this->createForm(SearchForm::class,$search);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            if ($search->choice=="Users"){
                $users=$userRepository->findSearch($search,$request);
                if ($search->view=="Normal"){
                    $view='search/usersSearch.html.twig';
                }
                else{
                    $view='search/sliderUser.html.twig';
                }
                return$this->render($view,[
                    'users'=>$users,
                    'tag'=>$search->motCle,
                    'type'=>'User',
                ]);
            }
            if ($search->choice=="Description"){
                $posts=$postRepository->findDescriptipn($search,$request);
                if ($search->view=="Normal"){
                    $view='search/searchPost.html.twig';
                }
                else{
                    $view='search/sliderPost.html.twig';
                }
                return$this->render($view,[
                    'ok'=>'1',
                    'posts'=>$posts,
                    'tag'=>$search->motCle,
                    'type'=>'Description',
                    'userSuggestions'=> $userSuggestions
                ]);
            }
               if ($search->choice=="Tags"){
                    $posts=$postRepository->findByTagName($search,$request);
                    if ($search->view=="Normal"){
                        $view='search/searchPost.html.twig';
                    }
                    else{
                        $view='search/sliderPost.html.twig';
                    }
                    return$this->render($view,[
                        'posts'=>$posts,
                        'ok'=>'1',
                        'tag'=>$search->motCle,
                        'type'=>'TAG',
                        'userSuggestions'=> $userSuggestions
                    ]);
                }
             if ($search->choice=="Post Title"){
                 $posts=$postRepository->findByPostTitle($search,$request);
                 if ($search->view=="Normal"){
                     $view='search/searchPost.html.twig';
                 }
                 else{
                     $view='search/sliderPost.html.twig';
                 }
                 return $this->render($view,[
                     'posts'=>$posts,
                     'ok'=>'1',
                     'tag'=>$search->motCle,
                     'type'=>'Post',
                     'userSuggestions'=> $userSuggestions
                 ]);
             }

        }
        if ($request->query->get('choice')=="Users"){
            $users=$userRepository->findSearch($search,$request);
            if ($request->query->get('view')=="Normal"){
                $view='search/usersSearch.html.twig';
            }
            else{
                $view='search/sliderUser.html.twig';
            }
            return$this->render($view,[
                'users'=>$users,
                'ok'=>'1',
                'tag'=>$search->motCle,
                'type'=>'User',
                'userSuggestions'=> $userSuggestions
            ]);
        }
        if ($request->query->get('choice')=="Description"){
            $users=$postRepository->findDescriptipn($search,$request);
            if ($request->query->get('view')=="Normal"){
                $view='search/searchPost.html.twig';
            }
            else{
                $view='search/sliderPost.html.twig';
            }
            return$this->render($view,[
                'posts'=>$users,
                'ok'=>'1',
                'tag'=>$search->motCle,
                'type'=>'Post',
                'userSuggestions'=> $userSuggestions
            ]);
        }

       if ($request->query->get('choice')=="Post Title") {
            $posts = $postRepository->findByPostTitle($search, $request);
            if ($search->view=="Normal"){
                $view='search/searchPost.html.twig';
            }
            else{
                $view='search/sliderPost.html.twig';
            }
            return$this->render($view,[
                'posts'=>$posts,
                'ok'=>'1',
                'tag'=>$search->motCle,
                'type'=>'Post',
                'userSuggestions'=> $userSuggestions
            ]);
        }
        if ($request->query->get('choice')=="Tags"){
            $posts=$postRepository->findByTagName($search,$request);
            if ($search->view=="Normal"){
                $view='search/searchPost.html.twig';
            }
            else{
                $view='search/sliderPost.html.twig';
            }
            return$this->render($view,[
                'posts'=>$posts,
                'ok'=>'1',
                'tag'=>$search->motCle,
                'type'=>'TAG',
                'userSuggestions'=> $userSuggestions
            ]);
        }
        return $this->render('search/searchBar.html.twig', [
            'form1'=>$form->createView()
        ]);
    }

    /**
     * @param $id
     * @param PostRepository $repository
     * @param Request $request
     * @return Response
     * @Route("/tags/{id<\d+>}",name="tagg")
     */
public function tagFinder($id,PostRepository $repository,Request $request,TagRepository $tagRepository){
    $data =new SearchHome();
    $data->page=$request->get('page',1);
    $form =$this->createForm(FiltreForm::class,$data);
    $resultat=$tagRepository->findBy(array('id'=>$id));
    $form->handleRequest($request);
    if(count($resultat)==1) {
        $tag = $tagRepository->findTagById($id);
        $tagName = $tag->getValue();
        $posts = $repository->findPostByTag($tagName, $request,$data);
    if ($form->isSubmitted() && $form->isValid()){
        $minPost=$request->get('min');
        $maxPost=$request->get('max');
        if(($minPost>$maxPost) &&($maxPost!=null)){
            $this->addFlash('error_max<min', ' Error : le nombre de likes maximum doit être superieur au nombre de likes minimum');
            $posts=$repository->findTagCategory($tagName,$data,$request);
        }

    }

        return $this->render('search/postSearch.html.twig', [
            'posts' => $posts,
            'form'=>$form->createView(),
            'ok'=>'1',
            'type' => 'TAG',
            'tag' => $tagName
        ]);
    }
    else{
    return $this->render('search/postSearch.html.twig', [
            'ok'=>'0',
            'type' => 'TAG',
            'tag' =>'???'

        ]);   }
}

}
