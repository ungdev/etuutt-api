<?php

namespace App\Entity;

use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use App\Repository\CovoitAlertRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CovoitAlertRepository::class)]
#[ORM\Table(name: 'covoit_alerts')]
#[ORM\HasLifecycleCallbacks]
class CovoitAlert
{
    use TimestampsTrait;
    use UUIDTrait;

    /**
     * The relation between the CovoitAlert and the User that created it.
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'covoitAlerts')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    /**
     * The maximum price in cents (x100). It is optional.
     */
    #[Assert\Type('int')]
    #[Assert\Positive]
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $priceMax = null;

    /**
     * The first boundary of the Covoit starting date.
     */
    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startAt = null;

    /**
     * The second boundary of the Covoit starting date.
     */
    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endAt = null;

    /**
     * The ID of the start city based on this website : https://geoservices.ign.fr/services-web-essentiels.
     */
    #[Assert\Uuid]
    #[ORM\Column(type: 'uuid', nullable: true)]
    private $startCityId;

    /**
     * The ID of the end city based on this website : https://geoservices.ign.fr/services-web-essentiels.
     */
    #[Assert\Uuid]
    #[ORM\Column(type: 'uuid', nullable: true)]
    private $endCityId;

    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
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
