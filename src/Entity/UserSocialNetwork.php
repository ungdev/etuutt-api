<?php

namespace App\Entity;

use App\Entity\Traits\UUIDTrait;
use App\Repository\UserSocialNetworkRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity related to User that stores its SocialNetworks.
 */
#[ORM\Entity(repositoryClass: UserSocialNetworkRepository::class)]
#[ORM\Table(name: 'user_social_network')]
class UserSocialNetwork
{
    use UUIDTrait;

    /**
     * The relation to the User which have those SocialNetworks.
     */
    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'socialNetwork', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * The URL of the User's Facebook.
     */
    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    #[Assert\Url]
    #[Assert\Regex('/^https:\/\/facebook\.com\/[a-z0-9]+(?:-[a-z0-9]+)*$/')]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $facebook = null;

    /**
     * The URL of the User's Twitter.
     */
    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    #[Assert\Url]
    #[Assert\Regex('/^https:\/\/twitter\.com\/[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*$/')]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $twitter = null;

    /**
     * The URL of the User's Instagram.
     */
    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    #[Assert\Url]
    #[Assert\Regex('/^https:\/\/instagram\.com\/[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*$/')]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $instagram = null;

    /**
     * The URL of the User's LinkedIn.
     */
    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    #[Assert\Url]
    #[Assert\Regex('/^https:\/\/linkedin\.com\/[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*$/')]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $linkedin = null;

    /**
     * The Discord pseudo of the User. It is usefull to create a link to discord bot.
     */
    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $pseudoDiscord = null;

    /**
     * A boolean to store if the User wants to be added to the UTT's discord.
     */
    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    #[Assert\Type('bool')]
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $wantDiscordUTT = false;

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
