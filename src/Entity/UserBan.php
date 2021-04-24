<?php

namespace App\Entity;

use App\Repository\UserBanRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserBanRepository::class)
 * @ORM\Table(name="user_ban")
 */
class UserBan
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\DateTime
     */
    private $readOnlyExpiration;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\DateTime
     */
    private $bannedExpiration;

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

    public function getReadOnlyExpiration(): ?\DateTimeInterface
    {
        return $this->readOnlyExpiration;
    }

    public function setReadOnlyExpiration(?\DateTimeInterface $readOnlyExpiration): self
    {
        $this->readOnlyExpiration = $readOnlyExpiration;

        return $this;
    }

    public function getBannedExpiration(): ?\DateTimeInterface
    {
        return $this->bannedExpiration;
    }

    public function setBannedExpiration(?\DateTimeInterface $bannedExpiration): self
    {
        $this->bannedExpiration = $bannedExpiration;

        return $this;
    }
}
