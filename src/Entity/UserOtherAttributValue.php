<?php

namespace App\Entity;

use App\Repository\UserOtherAttributValueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * This entity gives a value to a profil attribut of a User.
 */
#[ORM\Entity(repositoryClass: UserOtherAttributValueRepository::class)]
#[ORM\Table(name: 'user_other_attributs_values')]
class UserOtherAttributValue
{
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    /**
     * The relation to the User.
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'otherAttributs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * The relation to the attribut.
     */
    #[ORM\ManyToOne(targetEntity: UserOtherAttribut::class)]
    #[ORM\JoinColumn(name: 'attribut_name', referencedColumnName: 'name')]
    private ?UserOtherAttribut $attribut = null;

    /**
     * The value given to the attribut.
     */
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $value = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

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
