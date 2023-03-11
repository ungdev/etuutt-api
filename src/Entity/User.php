<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Controller\GetEDTController;
use App\Controller\SoftDeleteController;
use App\DataProvider\UserDataVisibilityItemDataProvider;
use App\Entity\Traits\UUIDTrait;
use App\Filter\SoftDeletedFilter;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The main entity that represents all Users. It is related to UEs, Covoits, Assos and others.
 */
#[
    ApiResource(
        shortName: 'user',
        operations: [
            new GetCollection(
                normalizationContext: ['groups' => ['user:read:some']],
            ),
            new Get(
                normalizationContext: ['groups' => ['user:read:one']],
                provider: UserDataVisibilityItemDataProvider::class
            ),
            new Get(
                uriTemplate: '/user/{id}/edt',
                controller: GetEDTController::class,
                openapiContext: ['summary' => "retrieves a user's schedule"],
                normalizationContext: ['groups' => ['user-edt:read:one']],
            ),
            new Delete(
                controller: SoftDeleteController::class,
                security: "is_granted('ROLE_ADMIN')",
            ),
            new Patch(
                normalizationContext: ['groups' => ['user:read:one']],
                denormalizationContext: ['groups' => ['user:write:update']],
                security: "object == user or is_granted('ROLE_ADMIN')",
            ),
        ],
        normalizationContext: [
            'skip_null_values' => false,
        ],
        paginationItemsPerPage: 10,
        security: "is_granted('ROLE_USER')",
    )
]
#[ApiFilter(SoftDeletedFilter::class)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface
{
    use UUIDTrait;

    #[Groups([
        'user:read:one',
        'user:read:some',
    ])]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private Uuid $id;

    /**
     * The CAS login of the User.
     */
    #[Groups([
        'user:read:one',
        'user:read:some',
    ])]
    #[Assert\Length(max: 50)]
    #[Assert\Regex('/^[a-z_0-9]{1,50}$/')]
    #[ORM\Column(type: Types::STRING, length: 50, unique: true)]
    private ?string $login = null;

    /**
     * For the User that are students, this is the UTT student number.
     */
    #[Groups([
        'user:read:one',
    ])]
    #[Assert\Positive]
    #[ORM\Column(type: Types::INTEGER, nullable: true, unique: true)]
    private ?int $studentId = null;

    #[Groups([
        'user:read:one',
        'user:read:some',
    ])]
    #[Assert\Length(max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $firstName = null;

    #[Groups([
        'user:read:one',
        'user:read:some',
    ])]
    #[Assert\Length(max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $lastName = null;

    /**
     * The roles of the user. Admin, UE editor, UE comment moderator...
     */
    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    /**
     * The relation to the entity that contains the User's Timestamps.
     */
    #[ORM\OneToOne(targetEntity: UserTimestamps::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserTimestamps $timestamps = null;

    /**
     * The relation to the entity that contains the User's SocialNetwork.
     */
    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    #[Assert\Valid]
    #[ORM\OneToOne(targetEntity: UserSocialNetwork::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserSocialNetwork $socialNetwork = null;

    /**
     * The possibles relations to the entities that contains the User's Bans.
     *
     * @var Collection<int, UserBan>|UserBan[]
     */
    #[ORM\OneToMany(targetEntity: UserBan::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private Collection $bans;

    /**
     * The relation to the entity that contains the User's RGPD.
     */
    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    #[Assert\Valid]
    #[ORM\OneToOne(targetEntity: UserRGPD::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserRGPD $RGPD = null;

    /**
     * The possibles relations to the entities that contains the User's BDEContributions.
     *
     * @var Collection<int, UserBDEContribution>|UserBDEContribution[]
     */
    #[ORM\OneToMany(targetEntity: UserBDEContribution::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private Collection $BDEContributions;

    /**
     * The relation to the badges that this User owns.
     */
    #[Groups([
        'user:read:one',
    ])]
    #[ORM\ManyToMany(targetEntity: Badge::class, mappedBy: 'users')]
    private Collection $badges;

    /**
     * The relation to all Covoits created by this User.
     *
     * @var Collection<int, Covoit>|Covoit[]
     */
    #[ORM\OneToMany(targetEntity: Covoit::class, mappedBy: 'author', orphanRemoval: true)]
    private Collection $createdCovoits;

    /**
     * The relation to all Covoits in which the User is subscribed.
     */
    #[ORM\ManyToMany(targetEntity: Covoit::class, mappedBy: 'passengers')]
    private Collection $passengerCovoits;

    /**
     * The relation to all alerts made by the User.
     *
     * @var Collection<int, CovoitAlert>|CovoitAlert[]
     */
    #[ORM\OneToMany(targetEntity: CovoitAlert::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $covoitAlerts;

    /**
     * @var AssoMembership[]|Collection<int, AssoMembership>
     */
    #[ORM\OneToMany(targetEntity: AssoMembership::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $assoMembership;

    /**
     * The relation to the Branche of the User.
     */
    #[Groups([
        'user:read:one',
        'user:read:some',
    ])]
    #[ORM\OneToOne(targetEntity: UserBranche::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserBranche $branche = null;

    /**
     * The relation to the Formation of the User.
     */
    #[Groups([
        'user:read:one',
        'user:read:some',
    ])]
    #[ORM\OneToOne(targetEntity: UserFormation::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserFormation $formation = null;

    /**
     * The relation to all group in which there is this User.
     */
    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'members')]
    private Collection $groups;

    /**
     * The relation to the Preference of the User.
     */
    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    #[Assert\Valid]
    #[ORM\OneToOne(targetEntity: UserPreference::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserPreference $preference = null;

    /**
     * The relation to the Infos of the User.
     */
    #[Groups([
        'user:read:one',
        'user:read:some',
        'user:write:update',
    ])]
    #[Assert\Valid]
    #[ORM\OneToOne(targetEntity: UserInfos::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserInfos $infos = null;

    /**
     * The relation to the Addresses of the User.
     *
     * @var Collection<int, UserAddress>|UserAddress[]
     */
    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    #[Assert\Valid]
    #[ORM\OneToMany(targetEntity: UserAddress::class, mappedBy: 'user', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $addresses;

    /**
     * The relation to mails and phone number of the User.
     */
    #[Groups([
        'user:read:one',
        'user:read:some',
        'user:write:update',
    ])]
    #[Assert\Valid]
    #[ORM\OneToOne(targetEntity: UserMailsPhones::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserMailsPhones $mailsPhones = null;

    /**
     * The relation to OtherAttributs made by the User.
     *
     * @var Collection<int, UserOtherAttributValue>|UserOtherAttributValue[]
     */
    #[ORM\OneToMany(targetEntity: UserOtherAttributValue::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $otherAttributs;

    /**
     * The relation to all UEsSubscriptions of the User.
     *
     * @var Collection<int, UserUESubscription>|UserUESubscription[]
     */
    #[ORM\OneToMany(targetEntity: UserUESubscription::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $UEsSubscriptions;

    /**
     * The relation to all UEVotes made by this User.
     *
     * @var Collection<int, UEStarVote>|UEStarVote[]
     */
    #[ORM\OneToMany(targetEntity: UEStarVote::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $UEStarVotes;

    /**
     * The relation to all courses of this User.
     */
    #[Groups([
        'user-edt:read:one',
    ])]
    #[ORM\ManyToMany(targetEntity: UECourse::class, mappedBy: 'students')]
    private Collection $courses;

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

        $this->setTimestamps(new UserTimestamps());
        $this->setSocialNetwork(new UserSocialNetwork());
        $this->setRGPD(new UserRGPD());
        $this->setPreference(new UserPreference());
        $this->setInfos(new UserInfos());
        $this->addAddress(new UserAddress());
        $this->setMailsPhones(new UserMailsPhones());
    }

    /**
     * A unique identifier that represents this user. This method is used by the Symfony User system.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    /**
     * A unique identifier that represents this user. This method is used by the Symfony User system. Deprecated but no alternative.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->login;
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

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): self
    {
        if (!\in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(string $role): self
    {
        if (\in_array($role, $this->roles, true)) {
            $this->roles = array_diff($this->roles, [$role]);
        }

        return $this;
    }

    /**
     * This method is not needed for apps that do not check user passwords. Mandatory definition by the 'UserInterface' interface.
     *
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return null;
    }

    /**
     * This method is not needed for apps that do not check user passwords. Mandatory definition by the 'UserInterface' interface.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     *  Not used but mandatory definition by the 'UserInterface' interface.
     *
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    /**
     * Method called by ORM before inserting changes on the user into the DB. It updates the `updatedAt` property.
     */
    #[ORM\PreUpdate]
    public function updateTimestamp(): self
    {
        $this->getTimestamps()->updateTimestamp();

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
        // set the owning side to null (unless already changed)
        if ($this->bans->removeElement($ban) && $ban->getUser() === $this) {
            $ban->setUser(null);
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
        // set the owning side to null (unless already changed)
        if ($this->BDEContributions->removeElement($bDEContribution) && $bDEContribution->getUser() === $this) {
            $bDEContribution->setUser(null);
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
        // set the owning side to null (unless already changed)
        if ($this->createdCovoits->removeElement($createdCovoit) && $createdCovoit->getAuthor() === $this) {
            $createdCovoit->setAuthor(null);
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
        // set the owning side to null (unless already changed)
        if ($this->assoMembership->removeElement($assoMembership) && $assoMembership->getUser() === $this) {
            $assoMembership->setUser(null);
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
        // set the owning side to null (unless already changed)
        if ($this->addresses->removeElement($address) && $address->getUser() === $this) {
            $address->setUser(null);
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
        // set the owning side to null (unless already changed)
        if ($this->otherAttributs->removeElement($otherAttribut) && $otherAttribut->getUser() === $this) {
            $otherAttribut->setUser(null);
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
        // set the owning side to null (unless already changed)
        if ($this->UEsSubscriptions->removeElement($userUESubscription) && $userUESubscription->getUser() === $this) {
            $userUESubscription->setUser(null);
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
        // set the owning side to null (unless already changed)
        if ($this->covoitAlerts->removeElement($covoitAlert) && $covoitAlert->getUser() === $this) {
            $covoitAlert->setUser(null);
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
        // set the owning side to null (unless already changed)
        if ($this->UEStarVotes->removeElement($uEStarVote) && $uEStarVote->getUser() === $this) {
            $uEStarVote->setUser(null);
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
