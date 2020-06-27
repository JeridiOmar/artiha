<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\SearchForm;
use App\Search\SearchEntity;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\DocBlock\Tag;
use phpDocumentor\Reflection\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
                    'type'=>'User'
                ]);
            }
            if ($search->choice=="Tags"){
                $posts=$postRepository->findByTagName($search,$request);
                if ($search->view=="Normal"){
                    $view='search/postSearch.html.twig';
                }
                else{
                    $view='search/sliderPost.html.twig';
                }
                return$this->render($view,[
                    'posts'=>$posts,
                    'tag'=>$search->motCle,
                    'type'=>'TAG'
                ]);
            }
            if ($search->choice=="Post Title"){
                $posts=$postRepository->findByPostTitle($search,$request);
                if ($search->view=="Normal"){
                    $view='search/postSearch.html.twig';
                }
                else{
                    $view='search/sliderPost.html.twig';
                }
                return$this->render($view,[
                    'posts'=>$posts,
                    'tag'=>$search->motCle,
                    'type'=>'Post'
                ]);
            }
            if ($search->choice=="Description"){
                $posts=$postRepository->findDescriptipn($search,$request);
                if ($search->view=="Normal"){
                    $view='search/postSearch.html.twig';
                }
                else{
                    $view='search/sliderPost.html.twig';
                }
                return$this->render($view,[
                    'posts'=>$posts,
                    'tag'=>$search->motCle,
                    'type'=>'Description'
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
                'tag'=>$search->motCle,
                'type'=>'User'
            ]);
        }
        if ($request->query->get('view')=="Description"){
            $posts=$postRepository->findDescriptipn($search,$request);
            if ($search->view=="Normal"){
                $view='search/postSearch.html.twig';
            }
            else{
                $view='search/sliderPost.html.twig';
            }
            return$this->render($view,[
                'posts'=>$posts,
                'tag'=>$search->motCle,
                'type'=>'Description'
            ]);
        }
        if ($request->query->get('view')=="Post Title") {
            $posts = $postRepository->findByPostTitle($search, $request);
            if ($search->view=="Normal"){
                $view='search/postSearch.html.twig';
            }
            else{
                $view='search/sliderPost.html.twig';
            }
            return$this->render($view,[
                'posts'=>$posts,
                'tag'=>$search->motCle,
                'type'=>'Post'
            ]);
        }
        if ($request->query->get('view')=="Tags"){
            $posts=$postRepository->findByTagName($search,$request);
            if ($search->view=="Normal"){
                $view='search/postSearch.html.twig';
            }
            else{
                $view='search/sliderPost.html.twig';
            }
            return$this->render($view,[
                'posts'=>$posts,
                'tag'=>$search->motCle,
                'type'=>'TAG'
            ]);
        }
        return $this->render('search/search.html.twig', [
            'form'=>$form->createView()
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
    $tagName=$tagRepository->findTagById($id);
    $posts = $repository->findPostByTag($tagName,$request);
    dd($posts);
    return $this->render('search/postSearch.html.twig', [
        'posts' => $posts,
        'type'=>'TAG',
        'tag'=>'salem'
    ]);

}

}
