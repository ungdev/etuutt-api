<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The main entity that represents all Users. It is related to UEs, Covoits, Assos and others.
 *
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
     * @Assert\Uuid(versions=4)
     */
    private $id;

    /**
     * The CAS login of the User.
     *
     * @ORM\Column(type="string", length=50, unique=true)
     *
     * @Assert\Type("string")
     * @Assert\Length(max=50)
     * @Assert\Regex("/^[a-z_0-9]{1,50}$/")
     */
    private $login;

    /**
     * For the User that are students, this is the UTT student number.
     *
     * @ORM\Column(type="integer", nullable=true, unique=true)
     *
     * @Assert\Type("int")
     * @Assert\Positive
     */
    private $studentId;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     * @Assert\Regex("/^[A-Za-z- ]{1,255}$/")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     * @Assert\Regex("/^[A-Za-z- ]{1,255}$/")
     */
    private $lastName;

    /**
     * The relation to the entity that contains the User's Timestamps.
     *
     * @ORM\OneToOne(targetEntity=UserTimestamps::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $timestamps;

    /**
     * The relation to the entity that contains the User's SocialNetwork.
     *
     * @ORM\OneToOne(targetEntity=UserSocialNetwork::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $socialNetwork;

    /**
     * The possibles relations to the entities that contains the User's Bans.
     *
     * @ORM\OneToMany(targetEntity=UserBan::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $bans;

    /**
     * The relation to the entity that contains the User's RGPD.
     *
     * @ORM\OneToOne(targetEntity=UserRGPD::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $RGPD;

    /**
     * The possibles relations to the entities that contains the User's BDEContributions.
     *
     * @ORM\OneToMany(targetEntity=UserBDEContribution::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $BDEContributions;

    /**
     * The relation to the badges that this User owns.
     *
     * @ORM\ManyToMany(targetEntity=Badge::class, mappedBy="users")
     */
    private $badges;

    /**
     * The relation to all Covoits created by this User.
     *
     * @ORM\OneToMany(targetEntity=Covoit::class, mappedBy="author", orphanRemoval=true)
     */
    private $createdCovoits;

    /**
     * The relation to all Covoits in which the User is subscribed.
     *
     * @ORM\ManyToMany(targetEntity=Covoit::class, mappedBy="passengers")
     */
    private $passengerCovoits;

    /**
     * The relation to all alerts made by the User.
     *
     * @ORM\OneToMany(targetEntity=CovoitAlert::class, mappedBy="user", orphanRemoval=true)
     */
    private $covoitAlerts;

    /**
     * @ORM\OneToMany(targetEntity=AssoMembership::class, mappedBy="user", orphanRemoval=true)
     */
    private $assoMembership;

    /**
     * The relation to the Branche of the User.
     *
     * @ORM\OneToOne(targetEntity=UserBranche::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $branche;

    /**
     * The relation to the Formation of the User.
     *
     * @ORM\OneToOne(targetEntity=UserFormation::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $formation;

    /**
     * The relation to all group in which there is this User.
     *
     * @ORM\ManyToMany(targetEntity=Group::class, mappedBy="members")
     */
    private $groups;

    /**
     * The relation to the Preference of the User.
     *
     * @ORM\OneToOne(targetEntity=UserPreference::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $preference;

    /**
     * The relation to the Infos of the User.
     *
     * @ORM\OneToOne(targetEntity=UserInfos::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $infos;

    /**
     * The relation to the Addresses of the User.
     *
     * @ORM\OneToMany(targetEntity=UserAddress::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $addresses;

    /**
     * The relation to the Mail and phone nulber of the User.
     *
     * @ORM\OneToOne(targetEntity=UserMailsPhones::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $mailsPhones;

    /**
     * The relation to OtherAttributs made by the User.
     *
     * @ORM\OneToMany(targetEntity=UserOtherAttributValue::class, mappedBy="user", orphanRemoval=true)
     */
    private $otherAttributs;

    /**
     * The relation to all UEsSubscriptions of the User.
     *
     * @ORM\OneToMany(targetEntity=UserUESubscription::class, mappedBy="user", orphanRemoval=true)
     */
    private $UEsSubscriptions;

    /**
     * The relation to all UEVotes made by this User.
     *
     * @ORM\OneToMany(targetEntity=UEStarVote::class, mappedBy="user", orphanRemoval=true)
     */
    private $UEStarVotes;

    /**
     * The relation to all courses of this User.
     *
     * @ORM\ManyToMany(targetEntity=UECourse::class, mappedBy="students")
     */
    private $courses;

    public function __construct()
    {
        $this->bans = new ArrayCollection();
        $this->BDEContributions = new ArrayCollection();
        $this->badges = new ArrayCollection();
        $this->createdCovoits = new ArrayCollection();
        $this->passengerCovoits = new ArrayCollection();
        $this->covoitAlerts = new ArrayCollection();
        $this->assoMembership = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->addresses = new ArrayCollection();
        $this->otherAttributs = new ArrayCollection();
        $this->UEsSubscriptions = new ArrayCollection();
        $this->UEStarVotes = new ArrayCollection();
        $this->courses = new ArrayCollection();
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

    /**
     * @return Collection|UserBan[]
     */
    public function getBans(): Collection
    {
        return $this->bans;
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
     * @return AssoMembership[]|Collection
     */
    public function getAssoMembership(): Collection
    {
        return $this->assoMembership;
    }

    public function addAssoMembership(AssoMembership $assoMembership): self
    {
        if (!$this->assoMembership->contains($assoMembership)) {
            $this->assoMembership[] = $assoMembership;
            $assoMembership->setUser($this);
        }

        return $this;
    }

    public function removeAssoMembership(AssoMembership $assoMembership): self
    {
        if ($this->assoMembership->removeElement($assoMembership)) {
            // set the owning side to null (unless already changed)
            if ($assoMembership->getUser() === $this) {
                $assoMembership->setUser(null);
            }
        }

        return $this;
    }

    public function getUTTBranche(): ?UserBranche
    {
        return $this->branche;
    }

    public function setUTTBranche(UserBranche $branche): self
    {
        // set the owning side of the relation if necessary
        if ($branche->getUser() !== $this) {
            $branche->setUser($this);
        }

        $this->branche = $branche;

        return $this;
    }

    public function getUTTFormation(): ?UserFormation
    {
        return $this->formation;
    }

    public function setUTTFormation(UserFormation $formation): self
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
            $group->addMember($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            $group->removeMember($this);
        }

        return $this;
    }

    public function getPreference(): ?UserPreference
    {
        return $this->preference;
    }

    public function setPreference(UserPreference $preference): self
    {
        // set the owning side of the relation if necessary
        if ($preference->getUser() !== $this) {
            $preference->setUser($this);
        }

        $this->preference = $preference;

        return $this;
    }

    public function getInfos(): ?UserInfos
    {
        return $this->infos;
    }

    public function setInfos(UserInfos $infos): self
    {
        // set the owning side of the relation if necessary
        if ($infos->getUser() !== $this) {
            $infos->setUser($this);
        }

        $this->infos = $infos;

        return $this;
    }

    /**
     * @return Collection|UserAddress[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(UserAddress $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setUser($this);
        }

        return $this;
    }

    public function removeAddress(UserAddress $address): self
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }

        return $this;
    }

    public function getMailsPhones(): ?UserMailsPhones
    {
        return $this->mailsPhones;
    }

    public function setMailsPhones(UserMailsPhones $mailsPhones): self
    {
        // set the owning side of the relation if necessary
        if ($mailsPhones->getUser() !== $this) {
            $mailsPhones->setUser($this);
        }

        $this->mailsPhones = $mailsPhones;

        return $this;
    }

    /**
     * @return Collection|UserOtherAttributValue[]
     */
    public function getOtherAttributs(): Collection
    {
        return $this->otherAttributs;
    }

    public function addOtherAttribut(UserOtherAttributValue $otherAttribut): self
    {
        if (!$this->otherAttributs->contains($otherAttribut)) {
            $this->otherAttributs[] = $otherAttribut;
            $otherAttribut->setUser($this);
        }

        return $this;
    }

    public function removeOtherAttribut(UserOtherAttributValue $otherAttribut): self
    {
        if ($this->otherAttributs->removeElement($otherAttribut)) {
            // set the owning side to null (unless already changed)
            if ($otherAttribut->getUser() === $this) {
                $otherAttribut->setUser(null);
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
            $passengerCovoit->addPassenger($this);
        }

        return $this;
    }

    public function removePassengerCovoit(Covoit $passengerCovoit): self
    {
        if ($this->passengerCovoits->removeElement($passengerCovoit)) {
            $passengerCovoit->removePassenger($this);
        }

        return $this;
    }

    /**
     * @return Collection|UserUESubscription[]
     */
    public function getUEsSubscriptions(): Collection
    {
        return $this->UEsSubscriptions;
    }

    public function addUEsSubscription(UserUESubscription $userUESubscription): self
    {
        if (!$this->UEsSubscriptions->contains($userUESubscription)) {
            $this->UEsSubscriptions[] = $userUESubscription;
            $userUESubscription->setUser($this);
        }

        return $this;
    }

    public function removeUEsSubscription(UserUESubscription $userUESubscription): self
    {
        if ($this->UEsSubscriptions->removeElement($userUESubscription)) {
            // set the owning side to null (unless already changed)
            if ($userUESubscription->getUser() === $this) {
                $userUESubscription->setUser(null);
            }
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

    /**
     * @return Collection|UEStarVote[]
     */
    public function getUEStarVotes(): Collection
    {
        return $this->UEStarVotes;
    }

    public function addUEStarVote(UEStarVote $uEStarVote): self
    {
        if (!$this->UEStarVotes->contains($uEStarVote)) {
            $this->UEStarVotes[] = $uEStarVote;
            $uEStarVote->setUser($this);
        }

        return $this;
    }

    public function removeUEStarVote(UEStarVote $uEStarVote): self
    {
        if ($this->UEStarVotes->removeElement($uEStarVote)) {
            // set the owning side to null (unless already changed)
            if ($uEStarVote->getUser() === $this) {
                $uEStarVote->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UECourse[]
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(UECourse $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
            $course->addStudent($this);
        }

        return $this;
    }

    public function removeCourse(UECourse $course): self
    {
        if ($this->courses->removeElement($course)) {
            $course->removeStudent($this);
        }

        return $this;
    }
}
