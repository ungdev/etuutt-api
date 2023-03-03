<?php

namespace App\Entity;

use App\Repository\UEAnnalTypeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UEAnnalTypeRepository::class)]
#[ORM\Table(name: 'ue_annal_types')]
class UEAnnalType
{
    public function __construct(
        /**
         * The type name (e.g. "Médian").
         */
        #[Assert\Type('string')]
        #[Assert\Length(min: 1, max: 50)]
        #[ORM\Id]
        #[ORM\Column(type: Types::STRING, length: 50)]
        private readonly ?string $name = null
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
