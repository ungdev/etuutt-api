<?php

namespace App\Entity;

use App\Repository\UserBanRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity representing a Ban of a User, either a readOnly or a ban until a date.
 */
#[ORM\Entity(repositoryClass: UserBanRepository::class)]
#[ORM\Table(name: 'user_bans')]
class UserBan
{
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    /**
     * The relation to the banned User.
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'bans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * The date until which the User can only read data on this app.
     */
    #[Assert\Date]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $readOnlyExpiration = null;

    /**
     * The date until which the User is banned and can not access to this app.
     */
    #[Assert\Date]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $bannedExpiration = null;

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
