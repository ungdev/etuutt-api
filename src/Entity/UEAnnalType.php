<?php

namespace App\Entity;

use App\Repository\UEAnnalTypeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UEAnnalTypeRepository::class)
 * @ORM\Table(name="ue_annal_type")
 */
class UEAnnalType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    public function __construct(string $name = null)
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
