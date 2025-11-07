<?php declare(strict_types=1);

namespace App\Twig\Components;

use App\Entity\User;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Avatar')]
class Avatar
{
    public User $user;
}
