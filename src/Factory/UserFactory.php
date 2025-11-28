<?php declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

final class UserFactory extends PersistentObjectFactory
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public static function class(): string
    {
        return User::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'email' => self::faker()->unique()->safeEmail(),
            'password' => 'password',
        ];
    }

    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(fn(User $user) => $user->setPassword(
                $this->passwordHasher->hashPassword($user, $user->getPassword())
            ));
    }
}
