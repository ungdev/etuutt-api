<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CovoitAlertRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CovoitAlertRepository::class)
 * @ORM\Table(name="covoit_alerts")
 */
class CovoitAlert
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
     * @ORM\Column(type="smallint")
     *
     * @Assert\Type("int")
     * @Assert\Positive
     */
    private $priceMax;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $startCity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $endCity;

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

    public function getPriceMax(): ?int
    {
        return $this->priceMax;
    }

    public function setPriceMax(int $priceMax): self
    {
        $this->priceMax = $priceMax;

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

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getStartCity(): ?string
    {
        return $this->startCity;
    }

    public function setStartCity(string $startCity): self
    {
        $this->startCity = $startCity;

        return $this;
    }

    public function getEndCity(): ?string
    {
        return $this->endCity;
    }

    public function setEndCity(string $endCity): self
    {
        $this->endCity = $endCity;

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
