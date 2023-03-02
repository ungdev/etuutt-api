<?php

namespace App\Entity;

use App\Repository\AssoMemberPermissionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AssoMemberPermissionRepository::class)
 * @ORM\Table(name="asso_membership_permissions")
 */
class AssoMembershipPermission
{
    public function __construct(
        /**
         * The permission accorded in the association (e.g. "daymail", "events", "edit_desc").
         *
         * @ORM\Id
         * @ORM\Column(type="string", length=50)
         * @Assert\Type("string")
         * @Assert\Length(min=1, max=50)
         * @Assert\Regex("/^[a-z_]{1,50}/")
         */
        private ?string $name
    ) {}

    public function getName(): ?string
    {
        return $this->name;
    }
}
