<?php

namespace App\Entity;

use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use App\Repository\BadgeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BadgeRepository::class)]
#[ORM\Table(name: 'badges')]
#[ORM\HasLifecycleCallbacks]
class Badge
{
    use TimestampsTrait;
    use UUIDTrait;

    /**
     * The Serie is a group of Badge with the same idea (e.g. Badges that deal with being an asso member).
     */
    #[Assert\Length(max: 50)]
    #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
    private ?string $serie = null;

    /**
     * The Level is serves to determine which badge of a serie is more advanced.
     */
    #[Assert\Positive]
    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $level = null;

    #[Assert\Length(min: 1, max: 100)]
    #[ORM\Column(type: Types::STRING, length: 100)]
    private ?string $name = null;

    /**
     * The path to the picture of the badge.
     */
    #[Assert\Length(min: 1, max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $picture = null;

    /**
     * The Translation object that contains the translation of the description.
     */
    #[SerializedName('description')]
    #[ORM\ManyToOne(targetEntity: Translation::class, cascade: ['persist', 'remove'])]
    private ?Translation $descriptionTranslation = null;

    /**
     * The relation that allow to add many Badges to many Users.
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'badges')]
    #[ORM\JoinTable(name: 'users_badges')]
    #[ORM\JoinColumn(name: 'badge_id')]
    #[ORM\InverseJoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private Collection $users;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setDescriptionTranslation(new Translation());

        $this->users = new ArrayCollection();
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
