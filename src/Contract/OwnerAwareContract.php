<?php declare(strict_types=1);

namespace App\Contract;

use App\Entity\User;

interface OwnerAwareContract
{
    public function getOwner(): ?User;

    public function setOwner(User $user): static;
}
