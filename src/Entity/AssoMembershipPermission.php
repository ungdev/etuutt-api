<?php

namespace App\Entity;

use App\Repository\AssoMemberPermissionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AssoMemberPermissionRepository::class)]
#[ORM\Table(name: 'asso_membership_permissions')]
class AssoMembershipPermission
{
    public function __construct(
        /**
         * The permission accorded in the association (e.g. "daymail", "events", "edit_desc").
         */
        #[Assert\Length(min: 1, max: 50)]
        #[Assert\Regex('/^[a-z_]{1,50}/')]
        #[ORM\Id]
        #[ORM\Column(type: Types::STRING, length: 50)]
        private readonly ?string $name
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
