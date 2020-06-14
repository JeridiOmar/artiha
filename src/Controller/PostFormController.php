<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Post;
use App\Entity\Recording;
use App\Entity\Tag;
use App\Entity\Text;
use App\Entity\User;
use App\Entity\Video;
use App\Form\PostType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class PostFormController extends AbstractController
{
    /**
     * @Route("/post/text/{id}", name="post_form")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param User $user
     * @param TagRepository $tagrepo
     * @return Response
     */
    public function index(Request $request,EntityManagerInterface $entityManager,User $user,TagRepository $tagrepo)
    {
        $post=new Post();
        $form=$this->createForm(PostType::class,$post);
        $form->add('content')->add('submit',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $post->setCreatedAt(new \DateTime());
            $post->setType('text');
            $post->setUser($user);
            $tagData=explode(" ",$form->get('tags')->getData());
            if(!$tagData[0]==="") {
                foreach ($tagData as $item) {
                    if ($item[0] == "#") {
                        $item = str_replace("#", "", $item);
                        $tagfound = $tagrepo->findOneBy(['value' => $item]);
                        if (!isset($tagfound)) {
                            $tag = new Tag();
                            $tag->setValue($item);
                            $tag->addPost($post);
                            $entityManager->persist($tag);
                        } else {
                            $tagfound->addPost($post);
                        }
                    }
                }
            }

            $entityManager->persist($post);
            $entityManager->flush();
            return $this->render('post_form/index.html.twig', [
                'controller_name' => 'updatePersonController',
            ]);
        }

        return $this->render('post_form/text.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/post/picture/{id}")
     * @param Request $request
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @param TagRepository $tagrepo
     * @return Response
     */
    public function index2(Request $request,User $user,EntityManagerInterface $entityManager,TagRepository $tagrepo)
    {
        $post=new Post();
        $form=$this->createForm(PostType::class,$post);
        $form->add('picture',FileType::class,array('mapped'=>false,
            'required'=>true ,
            'constraints'=>[new Image()]))
            ->add('submit',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $post->setCreatedAt(new \DateTime());
            $post->setType('picture');
            if(isset($form['picture'])){
                $picture=$form['picture']->getData();
                $picpath=md5(uniqid()).$picture->getClientOriginalName();
                $destination=__DIR__."/../../public/uploads/files/post_pictures";
                try{
                    $picture->move($destination,$picpath);
                    $content=new Picture();
                    $content->setPicturePath('public/uploads/files/post_pictures/'.$picpath);
                    $post->setContent($content);
                }
                catch (FileException $fe){
                    echo $fe;
                }
            }
            $post->setUser($user);
            $tagData=explode(" ",$form->get('tags')->getData());
            if(!$tagData[0]===""){
                foreach ($tagData as $item) {
                    if($item[0]=="#"){
                        $item=str_replace("#","",$item);
                        $tagfound=$tagrepo->findOneBy(['value'=>$item]);
                        if(!isset($tagfound)){
                            $tag=new Tag();
                            $tag->setValue($item);
                            $tag->addPost($post);
                            $entityManager->persist($tag);
                        }
                        else{
                            $tagfound->addPost($post);
                        }
                    }
                }
            }

            $entityManager->persist($post);
            $entityManager->flush();
            return $this->render('post_form/index.html.twig', [
                'controller_name' => 'updatePersonController',
            ]);
        }

        return $this->render('post_form/picture.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/post/recording/{id}")
     * @param Request $request
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @param TagRepository $tagrepo
     * @return Response
     */
    public function index3(Request $request,User $user, EntityManagerInterface $entityManager,TagRepository $tagrepo)
    {
        $post=new Post();
        $form=$this->createForm(PostType::class,$post);
        $form->add('recording',FileType::class,array('mapped'=>false,
            'required'=>true ,
            'constraints'=>[new File(['mimeTypes'=>['audio/*']])]))
            ->add('submit',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $post->setCreatedAt(new \DateTime());
            $post->setType('recording');
            if(isset($form['recording'])){
                $rec=$form['recording']->getData();
                $recordingPath=md5(uniqid()).$rec->getClientOriginalName();
                $destination=__DIR__."/../../public/uploads/files/post_recordings";
                try{
                    $rec->move($destination,$recordingPath);
                    $content=new Recording();
                    $content->setRecordingPath('public/uploads/files/post_recordings/'.$recordingPath);
                    $post->setContent($content);
                }
                catch (FileException $fe){
                    echo $fe;
                }
            }
            $post->setUser($user);
            $tagData=explode(" ",$form->get('tags')->getData());
            if(!$tagData[0]===""){
            foreach ($tagData as $item) {
                if($item[0]=="#"){
                    $item=str_replace("#","",$item);
                    $tagfound=$tagrepo->findOneBy(['value'=>$item]);
                    if(!isset($tagfound)){
                        $tag=new Tag();
                        $tag->setValue($item);
                        $tag->addPost($post);
                        $entityManager->persist($tag);
                    }
                    else{
                        $tagfound->addPost($post);
                    }
                }
            }
            }

            $entityManager->persist($post);
            $entityManager->flush();
            return $this->render('post_form/index.html.twig', [
                'controller_name' => 'updatePersonController',
            ]);
        }

        return $this->render('post_form/recording.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/post/video/{id}")
     * @param Request $request
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @param TagRepository $tagrepo
     * @return Response
     */
    public function index4(Request $request,User $user,EntityManagerInterface $entityManager,TagRepository $tagrepo)
    {
        $post=new Post();
        $form=$this->createForm(PostType::class,$post);
        $form->add('video',FileType::class,array('mapped'=>false,
            'required'=>true ,
            'constraints'=>[new File(['mimeTypes'=>['video/*','application/x-mpegURL']])],
            ))
            ->add('thumbnail',FileType::class,array('mapped'=>false,
                'required'=>true,
                'constraints'=>[new Image()]))
            ->add('submit',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $post->setCreatedAt(new \DateTime());
            $post->setType('video');
            if($form['video']->getNormData()){
                $video=$form['video']->getData();
                $thumbnail=null;
                if($form['thumbnail']->getNormData()) {
                    $thumbnail=$form['thumbnail']->getData();
                    $thumbpath=md5(uniqid()).$thumbnail->getClientOriginalName();
                }
                $vidpath=md5(uniqid()).$video->getClientOriginalName();
                $destination=__DIR__."/../../public/uploads/files/post_videos";
                try{
                    $thumbnail->move($destination."/thumbnails",$thumbpath);
                    $video->move($destination,$vidpath);
                    $content=new Video();
                    $content->setVideoPath('public/uploads/files/post_videos/'.$vidpath);
                    $content->setThumbnailPath("public/uploads/files/post_videos/thumbnails/".$thumbpath);
                    $post->setContent($content);
                }
                catch (FileException $fe){
                    echo $fe;
                }
            }
            $post->setUser($user);
            $tagData=explode(" ",$form->get('tags')->getData());
            if(!$tagData[0]===""){
            foreach ($tagData as $item) {
                if($item[0]=="#"){
                    $item=str_replace("#","",$item);
                    $tagfound=$tagrepo->findOneBy(['value'=>$item]);
                    if(!isset($tagfound)){
                        $tag=new Tag();
                        $tag->setValue($item);
                        $tag->addPost($post);
                        $entityManager->persist($tag);
                    }
                    else{
                        $tagfound->addPost($post);
                    }
                }
            }}

            $entityManager->persist($post);
            $entityManager->flush();
            return $this->render('post_form/index.html.twig', [
                'controller_name' => 'updatePersonController',
            ]);
        }

        return $this->render('post_form/video.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
