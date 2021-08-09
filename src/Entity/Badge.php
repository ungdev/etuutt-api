<?php

namespace App\Entity;

use App\Repository\BadgeRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BadgeRepository::class)
 * @ORM\Table(name="badges")
 */
class Badge
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     *
     * @Assert\Uuid(versions={4})
     */
    private $id;

    /**
     * The Serie is a group of Badge with the same idea (e.g. Badges that deal with being an asso member).
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @Assert\Type("string")
     * @Assert\Length(max=50)
     */
    private $serie;

    /**
     * The Level is serves to determine which badge of a serie is more advanced.
     *
     * @ORM\Column(type="smallint", nullable=true)
     *
     * @Assert\Type("int")
     * @Assert\Positive
     */
    private $level;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=100)
     */
    private $name;

    /**
     * The path to the picture of the badge.
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=255)
     */
    private $picture;

    /**
     * The Translation object that contains the translation of the description.
     *
     * @ORM\ManyToOne(targetEntity=Translation::class)
     * @ORM\JoinColumn(name="description_traduction_code", referencedColumnName="code")
     */
    private $descriptionTranslation;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\DateTime
     */
    private $deletedAt;

    /**
     * The relation that allow to add many Badges to many Users.
     *
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="badges")
     * @ORM\JoinTable(
     *     name="users_badges",
     *     joinColumns={@ORM\JoinColumn(name="badge_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getSerie(): ?string
    {
        return $this->serie;
    }

    public function setSerie(string $serie): self
    {
        $this->serie = $serie;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): self
    {
        $this->level = $level;

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

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getDescriptionTranslation(): ?Translation
    {
        return $this->descriptionTranslation;
    }

    public function setDescriptionTranslation(?Translation $descriptionTranslation): self
    {
        $this->descriptionTranslation = $descriptionTranslation;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDeletedAt(): ?DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function isSoftDeleted(): bool
    {
        return !(null === $this->deletedAt);
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }
}
