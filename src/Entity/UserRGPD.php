<?php

namespace App\Entity;

use App\Repository\UserRGPDRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity related to User that stores its RGPD's infos.
 *
 * @ORM\Entity(repositoryClass=UserRGPDRepository::class)
 * @ORM\Table(name="user_rgpd")
 */
class UserRGPD
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
    private $isKeepingAccount;

    /**
     * A boolean to store if we delete all info about this User in our database.
     *
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @Assert\Type("bool")
     */
    private $isDeletingEverything;

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
