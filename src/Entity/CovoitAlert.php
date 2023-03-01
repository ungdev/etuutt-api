<?php

namespace App\Entity;

use App\Repository\CovoitAlertRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CovoitAlertRepository::class)
 * @ORM\Table(name="covoit_alerts")
 */
class CovoitAlert
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     * @Assert\Uuid
     */
    private ?Uuid $id = null;

    /**
     * The relation between the CovoitAlert and the User that created it.
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="covoitAlerts")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user = null;

    /**
     * The maximum price in cents (x100). It is optional.
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type("int")
     * @Assert\Positive
     */
    private ?int $priceMax = null;

    /**
     * The first boundary of the Covoit starting date.
     *
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     */
    private ?\DateTimeInterface $startAt = null;

    /**
     * The second boundary of the Covoit starting date.
     *
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     */
    private ?\DateTimeInterface $endAt = null;

    /**
     * The ID of the start city based on this website : https://geoservices.ign.fr/services-web-essentiels.
     *
     * @ORM\Column(type="uuid", nullable=true)
     * @Assert\Uuid
     */
    private $startCityId;

    /**
     * The ID of the end city based on this website : https://geoservices.ign.fr/services-web-essentiels.
     *
     * @ORM\Column(type="uuid", nullable=true)
     * @Assert\Uuid
     */
    private $endCityId;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     */
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     */
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

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

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getStartCityId()
    {
        return $this->startCityId;
    }

    public function setStartCityId($startCityId): self
    {
        $this->startCityId = $startCityId;

        return $this;
    }

    public function getEndCityId()
    {
        return $this->endCityId;
    }

    public function setEndCityId($endCityId): self
    {
        $this->endCityId = $endCityId;

        return $this;
    }
}
