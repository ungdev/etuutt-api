<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AssoGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=AssoGroupRepository::class)
 * @ORM\Table(name="asso_groups")
 */
class AssoGroup
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
     * @ORM\ManyToOne(targetEntity=Asso::class, inversedBy="assoGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $asso;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\Regex("/^[a-z_0-9]{1,50}$/")
     */
    private $slug;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\Type("int")
     * @Assert\Positive
     */
    private $position;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVisible;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=AssoMember::class, mappedBy="groupName", orphanRemoval=true)
     */
    private $assoMembers;

    public function __construct()
    {
        $this->assoMembers = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getAsso(): ?Asso
    {
        return $this->asso;
    }

    public function setAsso(?Asso $asso): self
    {
        $this->asso = $asso;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): self
    {
        $this->isVisible = $isVisible;

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

    /**
     * @return Collection|AssoMember[]
     */
    public function getAssoMembers(): Collection
    {
        return $this->assoMembers;
    }

    public function addAssoMember(AssoMember $assoMember): self
    {
        if (!$this->assoMembers->contains($assoMember)) {
            $this->assoMembers[] = $assoMember;
            $assoMember->setGroupName($this);
        }

        return $this;
    }

    public function removeAssoMember(AssoMember $assoMember): self
    {
        if ($this->assoMembers->removeElement($assoMember)) {
            // set the owning side to null (unless already changed)
            if ($assoMember->getGroupName() === $this) {
                $assoMember->setGroupName(null);
            }
        }

        return $this;
    }
}
