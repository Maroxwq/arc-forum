<?php

namespace App\Factory;

use App\Entity\Comment;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

final class CommentFactory extends PersistentObjectFactory
{
    public static function class(): string
    {
        return Comment::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'content' => self::faker()->sentences(random_int(1, 3), true),
            'owner' => UserFactory::randomOrCreate(),
        ];
    }
}
