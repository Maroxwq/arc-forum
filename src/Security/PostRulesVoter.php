<?php declare(strict_types=1);

namespace App\Security;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\CommentRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class PostRulesVoter extends Voter
{
    public const EDIT = 'post_edit';

    public function __construct(private CommentRepository $comments) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::EDIT && $subject instanceof Post;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        /** @var Post $post */
        $post = $subject;

        $owner = $post->getOwner();
        if (!$owner || $owner->getId() !== $user->getId()) {
            return false;
        }

        return $this->comments->count(['post' => $post]) <= 3;
    }
}
