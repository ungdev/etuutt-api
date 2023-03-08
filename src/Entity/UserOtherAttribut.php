<?php

namespace App\Entity;

use App\Repository\UserOtherAttributRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that represents custom field in a User profil.
 */
#[ORM\Entity(repositoryClass: UserOtherAttributRepository::class)]
#[ORM\Table(name: 'user_other_attributs')]
class UserOtherAttribut
{
    /**
     * The type of value that this attribut is.
     */
    #[Assert\Length(min: 1, max: 50)]
    #[Assert\Choice(['bool', 'int', 'float', 'string', 'longtext', 'date', 'datetime'])]
    #[ORM\Column(type: Types::STRING, length: 50)]
    private ?string $type = null;

    public function __construct(
        /**
         * The name of this field.
         */
        #[Assert\Length(min: 1, max: 100)]
        #[ORM\Id]
        #[ORM\Column(type: Types::STRING, length: 100)]
        private readonly ?string $name = null
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
