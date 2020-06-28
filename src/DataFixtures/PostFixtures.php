<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Picture;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\Text;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker=Factory::create();
        for ($i = 0; $i < 25; $i++) {
            $user=new User();

            $n=$faker->numberBetween(1,2);
            if ($n==1){
                $user->setSexe("male");
                $user->setPrenom($faker->titleMale);
            }
            else{
                $user->setSexe("female");
                $user->setPrenom($faker->titleFemale);
            }
            $user->setNom($faker->name);
            $user->setEmail($faker->email);
            $user->setPassword($faker->password);
            $user->setBio($faker->text(100));
            $user->setBirthday($faker->dateTime);
            $user->setNumTel($faker->numberBetween(21000000,29990970));
            $user->setProfilePicture($faker->imageUrl($width=640,$height=480));
            $user->setUsername($faker->userName());
            $user->setIsAdmin(false);
            $user->setIsDeleted(false);
            $manager->persist($user);
        }
        for ($i = 0; $i < 25; $i++) {
            $text=new Text();
            $text->setText($faker->text(150));
            $manager->persist($text);
        }
        for ($i = 0; $i < 25; $i++) {
            $tag=new Tag();
            $tag->setValue($faker->text(12));
            $manager->persist($tag);
        }
        for ($i = 0; $i < 100; $i++) {
            $image=new Picture();
            $image->setPicturePath($faker->imageUrl($width=640,$height=480));
            $manager->persist($image);
        }
  /*      for ($i = 0; $i < 100; $i++) {
            $post=new Post();
            $post->setTitle($faker->title);
            $post->setDescription($faker->text(150));
            $post->setCreatedAt($faker->dateTime);
            $post->setNblikes($faker->numberBetween(0,4040));
            $post->setNbcomment($faker->numberBetween(0,1004));

            $post->setUser();
        }*/



        $manager->flush();
    }
}
