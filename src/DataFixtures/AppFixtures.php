<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Post;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->unique()->safeEmail());
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $manager->persist($user);
            $users[] = $user;
        }
        $manager->flush();

        $posts = [];
        for ($i = 0; $i < 100; $i++) {
            $post = new Post();
            $post->setUser($users[array_rand($users)]);
            $post->setTitle($faker->sentence(3, true));
            $post->setContent($faker->paragraphs(3, true));
            $manager->persist($post);
            $posts[] = $post;
        }
        $manager->flush();

        for ($i = 0; $i < 300; $i++) {
            $comment = new Comment();
            $comment->setPost($posts[array_rand($posts)]);
            $comment->setUser($users[array_rand($users)]);
            $comment->setContent($faker->sentences(2, true));
            $manager->persist($comment);
        }
        $manager->flush();
    }
}
