<?php

namespace App\Factory;

use App\Entity\Post;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

final class PostFactory extends PersistentObjectFactory
{
    public static function class(): string
    {
        return Post::class;
    }

    protected function defaults(): array|callable
    {
        $rawTitle = self::faker()->words(random_int(3, 8), true);
        $title = mb_substr(trim(ucfirst($rawTitle)), 0, 64);

        return [
            'title' => $title,
            'content' => self::faker()->paragraphs(random_int(2, 6), true),
            'owner' => UserFactory::randomOrCreate(),
        ];
    }

    protected function initialize(): static
    {
        return $this
            ->afterPersist(function(Post $post): void {
                $r = random_int(1, 100);
                $commentsCount = ($r <= 5) ? random_int(30, 40) : (($r <= 30) ? random_int(6, 20) : random_int(0, 5));
                if ($commentsCount > 0) {
                    CommentFactory::createMany($commentsCount, function() use ($post) {
                        return [
                            'post' => $post,
                            'owner' => UserFactory::randomOrCreate(),
                            'content' => self::faker()->sentences(random_int(1, 3), true),
                        ];
                    });
                }
            });
    }

}
