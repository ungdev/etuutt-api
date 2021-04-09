<?php

namespace App\Entity;

use App\Repository\UserRGPDRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;

/**
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
     * @Assert\Uuid(
     *     versions = 4
     * )
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="RGPD", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * 
     * @Assert\Type("bool")
     */
    private $isKeepingAccount;

    /**
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
