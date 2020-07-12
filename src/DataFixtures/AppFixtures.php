<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Like;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker=Factory::create();
         $userRepository=new UserRepository();
         $postRepository=new PostRepository();
        for ($i = 0; $i < 1000; $i++){
            $like=new Like();

            $user=$userRepository->findUserById(rand([1,100]));
            $post=$postRepository->findPostById(rand(1,280));
            $like->setUser($user);
            $like->setPost($post);
            $manager->persist($like);

        }
        for ($i = 0; $i < 1000; $i++){
            $comment=new Comment();

            $user=$userRepository->findUserById(rand([1,100]));
            $post=$postRepository->findPostById(rand(1,280));
            $comment->setUser($user);
            $comment->setPost($post);
            $comment->setContent($faker->text(55));
            $manager->persist($comment);

        }
        $manager->flush();
    }
}
