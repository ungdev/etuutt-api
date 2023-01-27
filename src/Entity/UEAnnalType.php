<?php

namespace App\Entity;

use App\Repository\UEAnnalTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UEAnnalTypeRepository::class)
 * @ORM\Table(name="ue_annal_types")
 */
class UEAnnalType
{
    /**
     * The type name (e.g. "Médian").
     *
     * @ORM\Id
     * @ORM\Column(type="string", length=50)
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=50)
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
