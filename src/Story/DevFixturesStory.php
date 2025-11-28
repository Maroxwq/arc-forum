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
        $users = UserFactory::createMany(20);
        $getRandomUser = $this->listRandomizer($users, 20);
        $posts = PostFactory::createMany(100, fn() => ['owner' => $getRandomUser()]);
        $getRandomPost = $this->listRandomizer($posts, 10);
        CommentFactory::createMany(600, fn() => [
            'post' => $getRandomPost(),
            'owner' => $getRandomUser(),
        ]);
    }

    private function listRandomizer(array $elements, int $percentActive): \Closure
    {
        $faker = FakerFactory::create();
        $elementsQty = count($elements);
        $activeQty = (int) round($elementsQty * ($percentActive / 100));
        $elementsActive = array_slice($elements, 0, $activeQty);
        $elementsInactive = array_slice($elements, $activeQty, $elementsQty - $activeQty);
        $elementsGroups = [$elementsActive, $elementsInactive];

        return fn() => $faker->randomElement($faker->randomElement($elementsGroups));
    }
}
