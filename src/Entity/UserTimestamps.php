<?php

namespace App\Entity;

use App\Entity\Traits\SoftDeletableTrait;
use App\Entity\Traits\TimestampsTrait;
use App\Repository\UserTimestampsRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity related to User that stores its Timestamps.
 *
 * @ORM\Entity(repositoryClass=UserTimestampsRepository::class)
 * @ORM\Table(name="user_timestamps")
 * @ORM\HasLifecycleCallbacks
 */
class UserTimestamps
{
    use SoftDeletableTrait;
    use TimestampsTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     *
     * @Assert\Uuid
     */
    private $id;

    /**
     * The relation to the User which have those RGPD.
     *
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="timestamps", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Type("\DateTimeInterface")
     */
    private $firstLoginDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Type("\DateTimeInterface")
     */
    private $lastLoginDate;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
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

    public function getFirstLoginDate(): ?DateTimeInterface
    {
        return $this->firstLoginDate;
    }

    public function setFirstLoginDate(?DateTimeInterface $firstLoginDate): self
    {
        $this->firstLoginDate = $firstLoginDate;

        return $this;
    }

    public function getLastLoginDate(): ?DateTimeInterface
    {
        return $this->lastLoginDate;
    }

    public function setLastLoginDate(?DateTimeInterface $lastLoginDate): self
    {
        $this->lastLoginDate = $lastLoginDate;

        return $this;
    }
}
