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
        // create users, 50 total, 10 active and 40 inactive
        $users = UserFactory::createMany(50);
        $activeUsers = array_slice($users, 0, 10);
        $inactiveUsers = array_slice($users, 10, 40);
        // duplicates active users to increase chance they own posts
        $ownersPool = array_merge($activeUsers, $activeUsers, $inactiveUsers);
        // create 40 posts, each owned by a random user from the ownersPool
        $posts = PostFactory::createMany(40, fn() => ['owner' => $faker->randomElement($ownersPool)]);

        // create 200 comments with random post and owner
        CommentFactory::createMany(200, fn() => [
            'post' => $faker->randomElement($posts),
            'owner' => $faker->randomElement($users),
        ]);
    }
}
