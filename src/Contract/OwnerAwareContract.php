<?php

namespace App\Contract;

use App\Entity\User;

interface OwnerAwareContract
{
    /**
     * Get the owner of the entity.
     *
     * @return User|null
     */
    public function getOwner(): ?User;

    /**
     * Set the owner of the entity.
     *
     * @param ?User $user
     */
    public function setOwner(?User $user): static;
}
