<?php

namespace App\Controller;

use App\Form\SearchForm;
use App\Home\SearchHome;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController
 * @package App\Controller
 * @Route("/home")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post")
     */
    public function index(PostRepository $repository,Request $request)
    {
        $data =new SearchHome();
        $data->page=$request->get('page',1);
        $form =$this->createForm(SearchForm::class,$data);
        $form->handleRequest($request);
        $posts=$repository->findSearch($data);

        if ($form->isSubmitted() && $form->isValid()){
            $minPost=$request->get('min');
            $maxPost=$request->get('max');
            if($minPost>$maxPost){
                $this->addFlash('error_max<min', ' Error : le nombre de likes maximum doit être superieur au nombre de likes minimum');
                $posts=$repository->findCategory($data);
            }

        }
        return $this->render('post/index.html.twig', [
            'posts'=>$posts,
            'form'=>$form->createView()
        ]);
    }
}
