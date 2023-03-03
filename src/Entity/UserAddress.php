<?php

namespace App\Entity;

use App\Repository\UserAdressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity related to User that stores its address.
 */
#[ORM\Entity(repositoryClass: UserAdressRepository::class)]
#[ORM\Table(name: 'user_address')]
class UserAddress
{
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    /**
     * The relation to the User which live at this address.
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $street = null;

    /**
     * The french postal code.
     */
    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    #[Assert\Type('string')]
    #[Assert\Length(max: 20)]
    #[Assert\Regex('/^$|^\d{2}\s?\d{3}$/')]
    #[ORM\Column(type: Types::STRING, length: 20, nullable: true)]
    private ?string $postalCode = null;

    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $city = null;

    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    #[Assert\Type('string')]
    #[Assert\Length(max: 50)]
    #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
    private ?string $country = null;

    /**
     * Relations to all groups that can access to this data.
     */
    #[ORM\ManyToMany(targetEntity: Group::class)]
    #[ORM\JoinTable(name: 'user_visibility_addresses')]
    #[ORM\JoinColumn(name: 'user_address_id')]
    #[ORM\InverseJoinColumn(name: 'group_id', referencedColumnName: 'id')]
    private Collection $addressVisibility;

    public function __construct()
    {
        $this->addressVisibility = new ArrayCollection();
    }

    public function caller($to_call, $arg): void
    {
        if (\is_callable([$this, $to_call])) {
            $this->{$to_call}($arg);
        }
    }

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

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getAddressVisibility(): Collection
    {
        return $this->addressVisibility;
    }

    public function addAddressVisibility(Group $addressVisibility): self
    {
        if (!$this->addressVisibility->contains($addressVisibility)) {
            $this->addressVisibility[] = $addressVisibility;
        }

        return $this;
    }

    public function removeAddressVisibility(Group $addressVisibility): self
    {
        $this->addressVisibility->removeElement($addressVisibility);

        return $this;
    }
}
