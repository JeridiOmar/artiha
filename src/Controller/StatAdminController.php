<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatAdminController extends AbstractController
{
    /**
     * @Route("/stat/admin", name="stat_admin")
     */
    public function Stat(UserRepository $ur)
    {
        $nbUsers= $ur->countUsers();
        dump($ur); die();

        return $this->render('stat_admin/index.html.twig', [
            'controller_name' => 'StatAdminController',
            'nbUser' =>$ur,
        ]);
    }
}
