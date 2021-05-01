<?php

namespace App\Entity;

use App\Repository\UserFormationRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserFormationRepository::class)
 * @ORM\Table(name="user_formations")
 */
class UserFormation
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
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="formation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=UTTFormation::class)
     * @ORM\JoinColumn(name="formation_name", referencedColumnName="name")
     */
    private $formation;

    /**
     * @ORM\ManyToOne(targetEntity=UTTFormationFollowingMethod::class)
     * @ORM\JoinColumn(name="following_method_name", referencedColumnName="name")
     */
    private $followingMethod;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUTTFormation(): ?UTTFormation
    {
        return $this->formation;
    }

    public function setUTTFormation(?UTTFormation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    public function getFollowingMethod(): ?UTTFormationFollowingMethod
    {
        return $this->followingMethod;
    }

    public function setFollowingMethod(?UTTFormationFollowingMethod $followingMethod): self
    {
        $this->followingMethod = $followingMethod;

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
}
