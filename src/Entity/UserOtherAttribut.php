<?php

namespace App\Entity;

use App\Repository\UserOtherAttributRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that represents custom field in a User profil.
 *
 * @ORM\Entity(repositoryClass=UserOtherAttributRepository::class)
 * @ORM\Table(name="user_other_attributs")
 */
class UserOtherAttribut
{
    /**
     * The name of this field.
     *
     * @ORM\Id
     * @ORM\Column(type="string", length=100)
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=100)
     */
    private ?string $name;

    /**
     * The type of value that this attribut is.
     *
     * @ORM\Column(type="string", length=50)
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=50)
     * @Assert\Choice({"bool", "int", "float", "string", "longtext", "date", "datetime"})
     */
    private ?string $type = null;

    public function __construct(string $name = null)
    {
        $this->name = $name;
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
