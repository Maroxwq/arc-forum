<?php declare(strict_types=1);

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
        return [
            'title' => ucfirst(self::faker()->text(64)),
            'content' => self::faker()->paragraphs(random_int(2, 6), true),
            'owner' => UserFactory::randomOrCreate(),
        ];
    }
}
