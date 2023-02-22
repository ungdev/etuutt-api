<?php

namespace App\Entity;

use App\Entity\Traits\UUIDTrait;
use App\Repository\UserBanRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity representing a Ban of a User, either a readOnly or a ban until a date.
 *
 * @ORM\Entity(repositoryClass=UserBanRepository::class)
 * @ORM\Table(name="user_bans")
 */
class UserBan
{
    use UUIDTrait;

    /**
     * The relation to the banned User.
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * The date until which the User can only read data on this app.
     *
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Date
     */
    private $readOnlyExpiration;

    /**
     * The date until which the User is banned and can not access to this app.
     *
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Date
     */
    private $bannedExpiration;

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
