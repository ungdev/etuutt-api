<?php

namespace App\Entity;

use App\Repository\UserTimestampsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity related to User that stores its Timestamps.
 *
 * @ORM\Entity(repositoryClass=UserTimestampsRepository::class)
 * @ORM\Table(name="user_timestamps")
 */
class UserTimestamps
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    #[Assert\Uuid]
    private ?Uuid $id = null;

    /**
     * The relation to the User which have those RGPD.
     *
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="timestamps", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Assert\Type('\DateTimeInterface')]
    private ?\DateTimeInterface $firstLoginDate = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Assert\Type('\DateTimeInterface')]
    private ?\DateTimeInterface $lastLoginDate = null;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Assert\Type('\DateTimeInterface')]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Assert\Type('\DateTimeInterface')]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Assert\Type('\DateTimeInterface')]
    private ?\DateTimeInterface $deletedAt = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

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

    public function getFirstLoginDate(): ?\DateTimeInterface
    {
        return $this->firstLoginDate;
    }

    public function setFirstLoginDate(?\DateTimeInterface $firstLoginDate): self
    {
        $this->firstLoginDate = $firstLoginDate;

        return $this;
    }

    public function getLastLoginDate(): ?\DateTimeInterface
    {
        return $this->lastLoginDate;
    }

    public function setLastLoginDate(?\DateTimeInterface $lastLoginDate): self
    {
        $this->lastLoginDate = $lastLoginDate;

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

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function isSoftDeleted(): bool
    {
        return null !== $this->deletedAt;
    }
}
