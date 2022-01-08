<?php

namespace App\Entity;

use App\Entity\Traits\UUIDTrait;
use App\Repository\UserRGPDRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity related to User that stores its RGPD's infos.
 *
 * @ORM\Entity(repositoryClass=UserRGPDRepository::class)
 * @ORM\Table(name="user_rgpd")
 */
class UserRGPD
{
    use UUIDTrait;

    /**
     * The relation to the User which have those RGPD.
     *
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="RGPD", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * A boolean to store if we keep the User's account in the database.
     *
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @Assert\Type("bool")
     */
    #[Groups([
        'user:write:update',
    ])]
    private $isKeepingAccount;

    /**
     * A boolean to store if we delete all info about this User in our database.
     *
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @Assert\Type("bool")
     */
    #[Groups([
        'user:write:update',
    ])]
    private $isDeletingEverything;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getIsKeepingAccount(): ?bool
    {
        return $this->isKeepingAccount;
    }

    public function setIsKeepingAccount(?bool $isKeepingAccount): self
    {
        $this->isKeepingAccount = $isKeepingAccount;

        return $this;
    }

    public function getIsDeletingEverything(): ?bool
    {
        return $this->isDeletingEverything;
    }

    public function setIsDeletingEverything(?bool $isDeletingEverything): self
    {
        $this->isDeletingEverything = $isDeletingEverything;

        return $this;
    }
}
