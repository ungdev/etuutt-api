<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AssoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=AssoRepository::class)
 * @ORM\Table(name="assos")
 */
class Asso
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
     * @ORM\Column(type="string", length=50, unique=true)
     * 
     * @Assert\Regex("/^[a-z_0-9]{1,50}$/")
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Traduction::class)
     * @ORM\JoinColumn(name="description_short_traduction_code", referencedColumnName="code")
     */
    private $descriptionShortTraduction;

    /**
     * @ORM\ManyToOne(targetEntity=Traduction::class)
     * @ORM\JoinColumn(name="description_traduction_code", referencedColumnName="code")
     */
    private $descriptionTraduction;

    /**
     * @ORM\Column(type="string", length=100)
     * 
     * @Assert\Email
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * 
     * @Assert\Regex("/^0[0-9]{9}$/")
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * 
     * @Assert\Url
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Assert\DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Assert\DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * 
     * @Assert\DateTime
     */
    private $deletedAt;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescriptionShortTraduction(): ?Traduction
    {
        return $this->descriptionShortTraduction;
    }

    public function setDescriptionShortTraduction(?Traduction $descriptionShortTraduction): self
    {
        $this->descriptionShortTraduction = $descriptionShortTraduction;

        return $this;
    }

    public function getDescriptionTraduction(): ?Traduction
    {
        return $this->descriptionTraduction;
    }

    public function setDescriptionTraduction(?Traduction $descriptionTraduction): self
    {
        $this->descriptionTraduction = $descriptionTraduction;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

}
