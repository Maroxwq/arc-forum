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
        $rawTitle = self::faker()->text(64);
        $title = mb_substr(trim(ucfirst($rawTitle)), 0, 64);

        return [
            'title' => $title,
            'content' => self::faker()->paragraphs(random_int(2, 6), true),
            'owner' => UserFactory::randomOrCreate(),
        ];
    }
}
