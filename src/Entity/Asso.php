<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Controller\SoftDeleteController;
use App\Entity\Traits\SoftDeletableTrait;
use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use App\Repository\AssoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The main entity that represents all Assos.
 */
#[
    ApiResource(
        shortName: 'asso',
        operations: [
            new GetCollection(
                normalizationContext: ['groups' => ['asso:read:some']],
            ),
            new Get(
                normalizationContext: ['groups' => ['asso:read:one']],
            ),
            new Delete(
                controller: SoftDeleteController::class,
                security: "is_granted('ROLE_ADMIN')",
            ),
            new Patch(
                normalizationContext: ['groups' => ['asso:read:one']],
                denormalizationContext: ['groups' => ['asso:write:update']],
                security: "object == user or is_granted('ROLE_ADMIN')",
            ),
        ],
        normalizationContext: [
            'skip_null_values' => false,
        ],
        order: ['name'],
        paginationItemsPerPage: 10,
    ),
    ApiFilter(SearchFilter::class, properties: ['name' => 'partial', 'keywords' => 'exact']),
]
#[ORM\Entity(repositoryClass: AssoRepository::class)]
#[ORM\Table(name: 'assos')]
#[ORM\HasLifecycleCallbacks]
class Asso
{
    use SoftDeletableTrait;
    use TimestampsTrait;
    use UUIDTrait;

    #[Groups([
        'asso:read:one',
        'asso:read:some',
    ])]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private Uuid $id;

    /**
     * The login used by the CAS.
     */
    #[Assert\Length(min: 1, max: 50)]
    #[Assert\Regex('/^[a-z_0-9]{1,50}$/')]
    #[ORM\Column(type: Types::STRING, length: 50, unique: true)]
    private ?string $login = null;

    #[Groups([
        'asso:read:one',
        'asso:read:some',
    ])]
    #[Assert\Length(min: 1, max: 100)]
    #[ORM\Column(type: Types::STRING, length: 100, unique: true)]
    private ?string $name = null;

    /**
     * The Translation object that contains the translation of the short description.
     */
    #[SerializedName('descriptionShort')]
    #[Groups([
        'asso:read:some',
    ])]
    #[ORM\ManyToOne(targetEntity: Translation::class, cascade: ['persist', 'remove'])]
    private ?Translation $descriptionShortTranslation = null;

    /**
     * The Translation object that contains the translation of the complete description.
     */
    #[SerializedName('description')]
    #[Groups([
        'asso:read:one',
    ])]
    #[ORM\ManyToOne(targetEntity: Translation::class, cascade: ['persist', 'remove'])]
    private ?Translation $descriptionTranslation = null;

    /**
     * The email address of the association.
     */
    #[Groups([
        'asso:read:one',
    ])]
    #[Assert\Length(min: 1, max: 100)]
    #[Assert\Email]
    #[ORM\Column(type: Types::STRING, length: 100)]
    private ?string $mail = null;

    /**
     * The phone number of the association.
     */
    #[Groups([
        'asso:read:one',
    ])]
    #[Assert\Length(min: 0, max: 30)]
    #[Assert\Regex('/^0[0-9]{9}$/')]
    #[ORM\Column(type: Types::STRING, length: 30, nullable: true)]
    private ?string $phoneNumber = null;

    /**
     * The website of the association. It is optional.
     */
    #[Groups([
        'asso:read:one',
    ])]
    #[Assert\Length(min: 0, max: 100)]
    #[Assert\Url]
    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private ?string $website = null;

    /**
     * Link to the logo of the association. It is optional.
     */
    #[Groups([
        'asso:read:some',
        'asso:read:one',
    ])]
    #[Assert\Length(min: 0, max: 100)]
    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private ?string $logo = null;

    #[Groups([
        'asso:read:one',
    ])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    /**
     * The relation to all Keywords of this Asso.
     */
    #[Groups([
        'asso:read:one',
    ])]
    #[ORM\ManyToMany(targetEntity: AssoKeyword::class, inversedBy: 'assos')]
    #[ORM\JoinTable(name: 'assos_keywords')]
    #[ORM\JoinColumn(name: 'asso_id')]
    #[ORM\InverseJoinColumn(name: 'keyword', referencedColumnName: 'name')]
    private Collection $keywords;

    /**
     * The relation to all assoMessages sent by this Asso.
     *
     * @var AssoMessage[]|Collection<int, AssoMessage>
     */
    #[ORM\OneToMany(targetEntity: AssoMessage::class, mappedBy: 'asso', orphanRemoval: true)]
    private Collection $assoMessages;

    /**
     * The relation to all events in which this Asso participate.
     */
    #[Groups([
        'asso:read:one',
    ])]
    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'assos')]
    private Collection $events;

    /**
     * The relation to all Groups of this Asso.
     *
     * @var Collection<int, Group>|Group[]
     */
    #[ORM\OneToMany(targetEntity: Group::class, mappedBy: 'asso')]
    private Collection $groups;

    /**
     * The relation to all AssoMemberships of this Asso.
     *
     * @var AssoMembership[]|Collection<int, AssoMembership>
     */
    #[Groups([
        'asso:read:one',
    ])]
    #[ORM\OneToMany(targetEntity: AssoMembership::class, mappedBy: 'asso', orphanRemoval: true)]
    private Collection $assoMemberships;

    public function __construct()
    {
        $this->setDescriptionShortTranslation(new Translation());
        $this->setDescriptionTranslation(new Translation());
        $this->setCreatedAt(new \DateTime());

        $this->keywords = new ArrayCollection();
        $this->assoMessages = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->assoMemberships = new ArrayCollection();
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

    public function getDescriptionShortTranslation(): ?Translation
    {
        return $this->descriptionShortTranslation;
    }

    public function setDescriptionShortTranslation(?Translation $descriptionShortTranslation): self
    {
        $this->descriptionShortTranslation = $descriptionShortTranslation;

        return $this;
    }

    public function getDescriptionTranslation(): ?Translation
    {
        return $this->descriptionTranslation;
    }

    public function setDescriptionTranslation(?Translation $descriptionTranslation): self
    {
        $this->descriptionTranslation = $descriptionTranslation;

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

    /**
     * @return AssoKeyword[]|Collection
     */
    public function getKeywords(): Collection
    {
        return $this->keywords;
    }

    public function addKeyword(AssoKeyword $keyword): self
    {
        if (!$this->keywords->contains($keyword)) {
            $this->keywords[] = $keyword;
        }

        return $this;
    }

    public function removeKeyword(AssoKeyword $keyword): self
    {
        $this->keywords->removeElement($keyword);

        return $this;
    }

    /**
     * @return AssoMessage[]|Collection
     */
    public function getAssoMessages(): Collection
    {
        return $this->assoMessages;
    }

    public function addAssoMessage(AssoMessage $assoMessage): self
    {
        if (!$this->assoMessages->contains($assoMessage)) {
            $this->assoMessages[] = $assoMessage;
            $assoMessage->setAsso($this);
        }

        return $this;
    }

    public function removeAssoMessage(AssoMessage $assoMessage): self
    {
        // set the owning side to null (unless already changed)
        if ($this->assoMessages->removeElement($assoMessage) && $assoMessage->getAsso() === $this) {
            $assoMessage->setAsso(null);
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->addAsso($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            $event->removeAsso($this);
        }

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
            $group->setAsso($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        // set the owning side to null (unless already changed)
        if ($this->groups->removeElement($group) && $group->getAsso() === $this) {
            $group->setAsso(null);
        }

        return $this;
    }

    /**
     * @return AssoMembership[]|Collection
     */
    public function getAssoMemberships(): Collection
    {
        return $this->assoMemberships;
    }

    public function addAssoMembership(AssoMembership $assoMember): self
    {
        if (!$this->assoMemberships->contains($assoMember)) {
            $this->assoMemberships[] = $assoMember;
            $assoMember->setAsso($this);
        }

        return $this;
    }

    public function removeAssoMembership(AssoMembership $assoMember): self
    {
        // set the owning side to null (unless already changed)
        if ($this->assoMemberships->removeElement($assoMember) && $assoMember->getAsso() === $this) {
            $assoMember->setAsso(null);
        }

        return $this;
    }

    #[Groups([
        'asso:read:some',
    ])]
    public function getMembershipsCount(): int
    {
        return $this->assoMemberships->count();
    }
}
