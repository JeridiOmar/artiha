<?php

namespace App\Controller;

use App\Form\FiltreForm;
use App\Search\SearchHome;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        $form =$this->createForm(FiltreForm::class,$data);
        $form->handleRequest($request);
        $posts=$repository->findSearch($data);

        if ($form->isSubmitted() && $form->isValid()){
            $minPost=$request->get('min');
            $maxPost=$request->get('max');
            if($minPost>$maxPost){
                $this->addFlash('error_max<min', ' Error : le nombre de likes maximum doit être superieur au nombre de likes minimum');
                $posts=$repository->findCategory($data,$request);
            }

        }
        return $this->render('post/index.html.twig', [
            'posts'=>$posts,
            'type'=>'1',
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/subscribers", name="subscribers_home")
     * @param Request $request
     * @param PostRepository $postRepository
     * @return Response
     */
    public function subscribers(Request $request,PostRepository $repository)
    {
        $data = new SearchHome();
        $user = $this->getUser();
        $data->page=$request->get('page',1);
        $form =$this->createForm(FiltreForm::class,$data);
        $form->handleRequest($request);
        $posts=$repository->findPost($user,$request,$data);

        if ($form->isSubmitted() && $form->isValid()){
            $minPost=$request->get('min');
            $maxPost=$request->get('max');
            if($minPost>$maxPost){
                $this->addFlash('error_max<min', ' Error : le nombre de likes maximum doit être superieur au nombre de likes minimum');
                $posts=$repository->findCategory($data,$request);
            }

        }
        return $this->render('post/index.html.twig', [
            'posts'=>$posts,
            'type'=>'2',
            'form'=>$form->createView()
        ]);
    }

}
