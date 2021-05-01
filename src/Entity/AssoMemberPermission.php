<?php

namespace App\Entity;

use App\Repository\AssoMemberPermissionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AssoMemberPermissionRepository::class)
 * @ORM\Table(name="permissions")
 */
class AssoMemberPermission
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
