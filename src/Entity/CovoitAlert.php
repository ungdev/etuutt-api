<?php

namespace App\Entity;

use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use App\Repository\CovoitAlertRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CovoitAlertRepository::class)
 * @ORM\Table(name="covoit_alerts")
 * @ORM\HasLifecycleCallbacks
 */
class CovoitAlert
{
    use TimestampsTrait;
    use UUIDTrait;

    /**
     * The relation between the CovoitAlert and the User that created it.
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="covoitAlerts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * The maximum price in cents (x100). It is optional.
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Assert\Type("int")
     * @Assert\Positive
     */
    private $priceMax;

    /**
     * The first boundary of the Covoit starting date.
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\Type("\DateTimeInterface")
     */
    private $startAt;

    /**
     * The second boundary of the Covoit starting date.
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\Type("\DateTimeInterface")
     */
    private $endAt;

    /**
     * The ID of the start city based on this website : https://geoservices.ign.fr/services-web-essentiels.
     *
     * @ORM\Column(type="uuid", nullable=true)
     *
     * @Assert\Uuid
     */
    private $startCityId;

    /**
     * The ID of the end city based on this website : https://geoservices.ign.fr/services-web-essentiels.
     *
     * @ORM\Column(type="uuid", nullable=true)
     *
     * @Assert\Uuid
     */
    private $endCityId;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
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
