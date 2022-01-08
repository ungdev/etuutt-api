<?php

namespace App\Entity;

use App\Entity\Traits\UUIDTrait;
use App\Repository\UserOtherAttributValueRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * This entity gives a value to a profil attribut of a User.
 *
 * @ORM\Entity(repositoryClass=UserOtherAttributValueRepository::class)
 * @ORM\Table(name="user_other_attributs_values")
 */
class UserOtherAttributValue
{
    use UUIDTrait;

    /**
     * The relation to the User.
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="otherAttributs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * The relation to the attribut.
     *
     * @ORM\ManyToOne(targetEntity=UserOtherAttribut::class)
     * @ORM\JoinColumn(name="attribut_name", referencedColumnName="name")
     */
    private $attribut;

    /**
     * The value given to the attribut.
     *
     * @ORM\Column(type="text")
     *
     * @Assert\Type("string")
     */
    private $value;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAttribut(): ?UserOtherAttribut
    {
        return $this->attribut;
    }

    public function setAttribut(?UserOtherAttribut $attribut): self
    {
        $this->attribut = $attribut;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
