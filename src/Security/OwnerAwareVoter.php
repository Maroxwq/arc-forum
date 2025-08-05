<?php

namespace App\Security;

use App\Contract\OwnerAwareContract;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class OwnerAwareVoter extends Voter
{
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE], true) && $subject instanceof OwnerAwareContract;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        /* @var OwnerAwareContract $subject */

        return match ($attribute) {self::EDIT, self::DELETE => $subject->getOwner()->getId() === $user->getId(), default => false};
    }
}
