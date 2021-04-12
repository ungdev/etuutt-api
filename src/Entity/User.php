<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\UserTimestamps;
use App\Entity\UserBan;
use App\Entity\UserBranche;
use App\Entity\UserSocialNetwork;
use App\Entity\UserRGPD;
use App\Entity\Badge;
use App\Repository\UserBanRepository;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="users")
 */
class User
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
    * @ORM\OneToMany(targetEntity=UserBan::class, mappedBy="user", cascade={"persist", "remove"})
    */
    protected $bans;

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
     */
    private $badges;

    /**
     * @ORM\OneToOne(targetEntity=UserBranche::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $branche;

    /**
     * @ORM\OneToOne(targetEntity=UserFormation::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $formation;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class, mappedBy="users")
     */
    private $groups;

    public function __construct()
    {
        $this->bans = new ArrayCollection();
        $this->BDEContributions = new ArrayCollection();
        $this->badges = new ArrayCollection();
        $this->groups = new ArrayCollection();
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
     * @return Collection|Badge[]
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

    public function getBranche(): ?UserBranche
    {
        return $this->branche;
    }

    public function setBranche(UserBranche $branche): self
    {
        // set the owning side of the relation if necessary
        if ($branche->getUser() !== $this) {
            $branche->setUser($this);
        }

        $this->branche = $branche;

        return $this;
    }

    public function getFormation(): ?UserFormation
    {
        return $this->formation;
    }

    public function setFormation(UserFormation $formation): self
    {
        // set the owning side of the relation if necessary
        if ($formation->getUser() !== $this) {
            $formation->setUser($this);
        }

        $this->formation = $formation;

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->addUser($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            $group->removeUser($this);
        }

        return $this;
    }
}
