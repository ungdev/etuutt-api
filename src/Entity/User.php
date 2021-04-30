<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\OneToMany(targetEntity=UserBan::class, mappedBy="user", cascade={"persist", "remove"})
     */
    protected $bans;
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     *
     * @Assert\Uuid(versions=4)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     *
     * @Assert\Regex("/^[a-z_0-9]{1,50}$/")
     */
    private $login;

    /**
     * @ORM\Column(type="integer", nullable=true, unique=true)
     *
     * @Assert\Type("int")
     * @Assert\Positive
     */
    private $studentId;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Regex("/^[A-Za-z- ]{1,255}$/")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Regex("/^[A-Za-z- ]{1,255}$/")
     */
    private $lastName;

    /**
     * @ORM\OneToOne(targetEntity=UserTimestamps::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $timestamps;

    /**
     * @ORM\OneToOne(targetEntity=UserSocialNetwork::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $socialNetwork;

    /**
     * @ORM\OneToOne(targetEntity=UserRGPD::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $RGPD;

    /**
     * @ORM\OneToMany(targetEntity=UserBDEContribution::class, mappedBy="user", orphanRemoval=true)
     */
    private $BDEContributions;

    /**
     * @ORM\ManyToMany(targetEntity=Badge::class, mappedBy="users")
     * @ORM\JoinTable(
     *     name="users_badges",
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     joinColumns={@ORM\JoinColumn(name="badge_id", referencedColumnName="id")}
     * )
     */
    private $badges;

    /**
     * @ORM\OneToMany(targetEntity=Covoit::class, mappedBy="author", orphanRemoval=true)
     */
    private $createdCovoits;

    /**
     * @ORM\ManyToMany(targetEntity=Covoit::class, mappedBy="users")
     */
    private $passengerCovoits;

    /**
     * @ORM\OneToMany(targetEntity=CovoitAlert::class, mappedBy="user", orphanRemoval=true)
     */
    private $covoitAlerts;

    public function __construct()
    {
        $this->bans = new ArrayCollection();
        $this->BDEContributions = new ArrayCollection();
        $this->badges = new ArrayCollection();
        $this->createdCovoits = new ArrayCollection();
        $this->passengerCovoits = new ArrayCollection();
        $this->covoitAlerts = new ArrayCollection();
    }

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

    public function getStudentId(): ?int
    {
        return $this->studentId;
    }

    public function setStudentId(?int $studentId): self
    {
        $this->studentId = $studentId;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getTimestamps(): ?UserTimestamps
    {
        return $this->timestamps;
    }

    public function setTimestamps(UserTimestamps $userTimestamps): self
    {
        // set the owning side of the relation if necessary
        if ($userTimestamps->getUser() !== $this) {
            $userTimestamps->setUser($this);
        }

        $this->timestamps = $userTimestamps;

        return $this;
    }

    public function getSocialNetwork(): ?UserSocialNetwork
    {
        return $this->socialNetwork;
    }

    public function setSocialNetwork(UserSocialNetwork $socialNetwork): self
    {
        // set the owning side of the relation if necessary
        if ($socialNetwork->getUser() !== $this) {
            $socialNetwork->setUser($this);
        }

        $this->socialNetwork = $socialNetwork;

        return $this;
    }

    public function addBan(UserBan $ban): self
    {
        if (!$this->bans->contains($ban)) {
            $this->bans[] = $ban;
            $ban->setUser($this);
        }

        return $this;
    }

    public function removeBan(UserBan $ban): self
    {
        if ($this->bans->removeElement($ban)) {
            // set the owning side to null (unless already changed)
            if ($ban->getUser() === $this) {
                $ban->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserBan[]
     */
    public function getBans(): Collection
    {
        return $this->bans;
    }

    public function getRGPD(): ?UserRGPD
    {
        return $this->RGPD;
    }

    public function setRGPD(UserRGPD $RGPD): self
    {
        // set the owning side of the relation if necessary
        if ($RGPD->getUser() !== $this) {
            $RGPD->setUser($this);
        }

        $this->RGPD = $RGPD;

        return $this;
    }

    /**
     * @return Collection|UserBDEContribution[]
     */
    public function getBDEContributions(): Collection
    {
        return $this->BDEContributions;
    }

    public function addBDEContribution(UserBDEContribution $bDEContribution): self
    {
        if (!$this->BDEContributions->contains($bDEContribution)) {
            $this->BDEContributions[] = $bDEContribution;
            $bDEContribution->setUser($this);
        }

        return $this;
    }

    public function removeBDEContribution(UserBDEContribution $bDEContribution): self
    {
        if ($this->BDEContributions->removeElement($bDEContribution)) {
            // set the owning side to null (unless already changed)
            if ($bDEContribution->getUser() === $this) {
                $bDEContribution->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Badge[]|Collection
     */
    public function getBadges(): Collection
    {
        return $this->badges;
    }

    public function addBadge(Badge $badge): self
    {
        if (!$this->badges->contains($badge)) {
            $this->badges[] = $badge;
            $badge->addUser($this);
        }

        return $this;
    }

    public function removeBadge(Badge $badge): self
    {
        if ($this->badges->removeElement($badge)) {
            $badge->removeUser($this);
        }

        return $this;
    }

    /**
     * Covoits où user est le créateur.
     *
     * @return Collection|Covoit[]
     */
    public function getCreatedCovoits(): Collection
    {
        return $this->createdCovoits;
    }

    public function addCreatedCovoit(Covoit $createdCovoit): self
    {
        if (!$this->createdCovoits->contains($createdCovoit)) {
            $this->createdCovoits[] = $createdCovoit;
            $createdCovoit->setAuthor($this);
        }

        return $this;
    }

    public function removeCreatedCovoit(Covoit $createdCovoit): self
    {
        if ($this->createdCovoits->removeElement($createdCovoit)) {
            // set the owning side to null (unless already changed)
            if ($createdCovoit->getAuthor() === $this) {
                $createdCovoit->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * Covoits où user est passager.
     *
     * @return Collection|Covoit[]
     */
    public function getPassengerCovoits(): Collection
    {
        return $this->passengerCovoits;
    }

    public function addPassengerCovoit(Covoit $passengerCovoit): self
    {
        if (!$this->passengerCovoits->contains($passengerCovoit)) {
            $this->passengerCovoits[] = $passengerCovoit;
            $passengerCovoit->addUser($this);
        }

        return $this;
    }

    public function removePassengerCovoit(Covoit $passengerCovoit): self
    {
        if ($this->passengerCovoits->removeElement($passengerCovoit)) {
            $passengerCovoit->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|CovoitAlert[]
     */
    public function getCovoitAlerts(): Collection
    {
        return $this->covoitAlerts;
    }

    public function addCovoitAlert(CovoitAlert $covoitAlert): self
    {
        if (!$this->covoitAlerts->contains($covoitAlert)) {
            $this->covoitAlerts[] = $covoitAlert;
            $covoitAlert->setUser($this);
        }

        return $this;
    }

    public function removeCovoitAlert(CovoitAlert $covoitAlert): self
    {
        if ($this->covoitAlerts->removeElement($covoitAlert)) {
            // set the owning side to null (unless already changed)
            if ($covoitAlert->getUser() === $this) {
                $covoitAlert->setUser(null);
            }
        }

        return $this;
    }
}
