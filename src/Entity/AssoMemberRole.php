<?php

namespace App\Entity;

use App\Repository\AssoMemberRoleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AssoMemberRoleRepository::class)
 * @ORM\Table(name="asso_member_roles")
 */
class AssoMemberRole
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
