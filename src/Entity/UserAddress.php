<?php

namespace App\Entity;

use App\Repository\UserAdressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserAdressRepository::class)
 * @ORM\Table(name="user_address")
 */
class UserAddress
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     * 
     * @Assert\Uuid(versions = 4)
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="adresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $country;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class)
     * @ORM\JoinTable(
     *      name="user_visibility_addresses",
     *      joinColumns={@ORM\JoinColumn(name="user_address_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_name", referencedColumnName="name")}
     * )
     */
    private $addressVisibility;

    public function __construct()
    {
        $this->addressVisibility = new ArrayCollection();
    }

    public function caller($to_call, $arg) {
        if (is_callable([$this, $to_call])) {
            $this->$to_call($arg);
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
