<?php

namespace App\Entity;

use App\Entity\Traits\SoftDeletableTrait;
use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use App\Repository\UserTimestampsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * The entity related to User that stores its Timestamps.
 */
#[ORM\Entity(repositoryClass: UserTimestampsRepository::class)]
#[ORM\Table(name: 'user_timestamps')]
#[ORM\HasLifecycleCallbacks]
class UserTimestamps
{
    use SoftDeletableTrait;
    use TimestampsTrait;
    use UUIDTrait;

    /**
     * The relation to the User which have those RGPD.
     */
    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'timestamps', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $firstLoginDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastLoginDate = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
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
}
