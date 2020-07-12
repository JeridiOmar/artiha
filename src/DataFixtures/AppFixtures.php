<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use phpDocumentor\Reflection\Types\This;

class AppFixtures extends Fixture
{
//    private $userRepository=null;
//    private $postRepository=null;
    private $container;
    public function load(ObjectManager $manager)
    {

        $faker=Factory::create();
         $userRepository=$this->container->get->getRepository(User::class);
         $postRepository=$manager->getRepository(Post::class);
        for ($i = 0; $i < 1000; $i++){
            $like=new Like();

            $user=$userRepository->find((int)rand(60,100));

            $post=$postRepository->find(rand(60,100));
            dump($user);
            $like->setUser($user);
            $like->setPost($post);
            $manager->persist($like);

        }
        for ($i = 0; $i < 1000; $i++){
            $comment=new Comment();
            dump(rand(60,100));
            $user=$userRepository->find(rand(60,100));

            $post=$postRepository->find(rand(60,100));
            $comment->setUser($user);
            //dump($user);
            $comment->setPost($post);
            $comment->setContent($faker->text(55));
            dd($comment);
            $manager->persist($comment);

        }
        $manager->flush();
    }
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
