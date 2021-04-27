<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UERepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass=UERepository::class)
 * @ORM\Table(name="ues")
 */
class UE
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     *
     * @Assert\Uuid(versions=4)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $validationRate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stillAvailable;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=UserUESubscription::class, mappedBy="UE", orphanRemoval=true)
     */
    private $usersSubscriptions;

    /**
     * @ORM\ManyToOne(targetEntity=Filiere::class, inversedBy="UEs")
     * @ORM\JoinColumn(name="filiere_code", referencedColumnName="code")
     */
    private $filiere;

    public function __construct()
    {
        $this->usersSubscriptions = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValidationRate(): ?float
    {
        return $this->validationRate;
    }

    public function setValidationRate(?float $validationRate): self
    {
        $this->validationRate = $validationRate;

        return $this;
    }

    public function getStillAvailable(): ?bool
    {
        return $this->stillAvailable;
    }

    public function setStillAvailable(bool $stillAvailable): self
    {
        $this->stillAvailable = $stillAvailable;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|UserUESubscription[]
     */
    public function getUserUESubscriptions(): Collection
    {
        return $this->usersSubscriptions;
    }

    public function addUserUESubscriptions(UserUESubscription $userUESubscription): self
    {
        if (!$this->UserUESubscriptions->contains($userUESubscription)) {
            $this->UserUESubscriptions[] = $userUESubscription;
            $userUESubscription->setUE($this);
        }

        return $this;
    }

    public function removeUserUESubscriptions(UserUESubscription $userUESubscription): self
    {
        if ($this->UserUESubscriptions->removeElement($userUESubscription)) {
            // set the owning side to null (unless already changed)
            if ($userUESubscription->getUE() === $this) {
                $userUESubscription->setUE(null);
            }
        }

        return $this;
    }

    public function getFiliere(): ?Filiere
    {
        return $this->filiere;
    }

    public function setFiliere(?Filiere $filiere): self
    {
        $this->filiere = $filiere;

        return $this;
    }
}
