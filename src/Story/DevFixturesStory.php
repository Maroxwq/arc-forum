<?php declare(strict_types=1);

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
        $faker = FakerFactory::create();
        $users = UserFactory::createMany(65);
        $activeUsers = array_slice($users, 0, 10);
        $regularUsers = array_slice($users, 10, 40);
        $rareUsers = array_slice($users, 50, 15);
        $ownersPool = array_merge($activeUsers, $activeUsers, $regularUsers, $rareUsers);
        $posts = PostFactory::createMany(80, fn() => ['owner' => $faker->randomElement($ownersPool)]);

        foreach ($posts as $post) {
            $chance = $faker->numberBetween(1, 100);
            $commentCount = $chance <= 5 ? $faker->numberBetween(30, 50) : ($chance <= 30 ? $faker->numberBetween(6, 20) : $faker->numberBetween(0, 5));
            if ($commentCount > 0) {
                CommentFactory::createMany($commentCount, fn() => [
                    'post' => $post,
                    'owner' => $faker->randomElement($users),
                ]);
            }
        }

        foreach (UserFactory::createMany(2) as $superUser) {
            PostFactory::createMany($faker->numberBetween(6, 12), ['owner' => $superUser]);
        }
    }
}
