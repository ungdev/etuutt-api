<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CovoitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CovoitRepository::class)
 * @ORM\Table(name="covoits")
 */
class Covoit
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
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="smallint")
     *
     * @Assert\Type("int")
     * @Assert\Positive
     */
    private $capacity;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFull;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\Type("int")
     * @Assert\Positive
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     *
     * @Assert\Url
     */
    private $blablacarUrl;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $startAdress;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $startDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $endAdress;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $endDate;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getIsFull(): ?bool
    {
        return $this->isFull;
    }

    public function setIsFull(bool $isFull): self
    {
        $this->isFull = $isFull;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBlablacarUrl(): ?string
    {
        return $this->blablacarUrl;
    }

    public function setBlablacarUrl(string $blablacarUrl): self
    {
        $this->blablacarUrl = $blablacarUrl;

        return $this;
    }

    public function getStartAdress(): ?string
    {
        return $this->startAdress;
    }

    public function setStartAdress(string $startAdress): self
    {
        $this->startAdress = $startAdress;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndAdress(): ?string
    {
        return $this->endAdress;
    }

    public function setEndAdress(string $endAdress): self
    {
        $this->endAdress = $endAdress;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

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

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
