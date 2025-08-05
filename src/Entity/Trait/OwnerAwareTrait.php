<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

trait OwnerAwareTrait
{
    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
