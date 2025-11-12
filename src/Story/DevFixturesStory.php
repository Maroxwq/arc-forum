<?php

namespace App\Story;

use App\Factory\UserFactory;
use App\Factory\PostFactory;
use App\Factory\CommentFactory;
use Faker\Factory as FakerFactory;
use Zenstruck\Foundry\Story;
use Zenstruck\Foundry\Attribute\AsFixture;

#[AsFixture(name: 'dev')]
final class DevFixturesStory extends Story
{
    public function build(): void
    {
        $f = FakerFactory::create();
        $power = UserFactory::createMany(8);
        $active = UserFactory::createMany(30);
        $regular = UserFactory::createMany(50);
        $rare = UserFactory::createMany(40);
        $all = array_merge($power, $active, $regular, $rare);

        for ($i = 0; $i < 100; $i++) {
            $r = random_int(1,100);
            $owner = $r <= 12 ? $f->randomElement($power) : ($r <= 40 ? $f->randomElement($active) : ($r <= 88 ? $f->randomElement($regular) : $f->randomElement($rare)));
            $post = PostFactory::createOne(['owner' => $owner]);
            $c = random_int(1,100);
            $comments = $c <= 5 ? random_int(30,80) : ($c <= 30 ? random_int(6,25) : random_int(0,5));
            if ($comments) {
                CommentFactory::createMany($comments, fn() => [
                    'post' => $post,
                    'owner' => $f->randomElement($all),
                    'content' => $f->sentences(random_int(1,3), true),
                ]);
            }
        }

        foreach (UserFactory::createMany(4) as $s) {
            PostFactory::createMany(random_int(8,20), ['owner' => $s]);
        }
    }
}
