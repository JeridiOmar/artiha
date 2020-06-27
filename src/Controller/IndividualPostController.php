<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
    public function index($id, PostRepository $postRepository)
    {
        $post = $postRepository->findPostById($id);
        return $this->render('individual_post/index.html.twig', [
            'post' => $post,
        ]);
    }
}
