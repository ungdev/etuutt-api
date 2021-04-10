<?php

namespace App\Entity;

use App\Repository\UserSocialNetworkRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;

use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;

/**
 * @ORM\Entity(repositoryClass=UserSocialNetworkRepository::class)
 * @ORM\Table(name="user_social_network")
 */
class UserSocialNetwork
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     * 
     * @Assert\Uuid(versions = 4)
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="socialNetwork", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Assert\Url
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Assert\Url
     */
    private $twitter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Assert\Url
     */
    private $instagram;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Assert\Url
     */
    private $linkedin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pseudoDiscord;

    /**
     * @ORM\Column(type="boolean")
     * 
     * @Assert\Type("bool")
     */
    private $wantDiscordUTT;

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

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getPseudoDiscord(): ?string
    {
        return $this->pseudoDiscord;
    }

    public function setPseudoDiscord(?string $pseudoDiscord): self
    {
        $this->pseudoDiscord = $pseudoDiscord;

        return $this;
    }

    public function getWantDiscordUTT(): ?bool
    {
        return $this->wantDiscordUTT;
    }

    public function setWantDiscordUTT(bool $wantDiscordUTT): self
    {
        $this->wantDiscordUTT = $wantDiscordUTT;

        return $this;
    }
}
