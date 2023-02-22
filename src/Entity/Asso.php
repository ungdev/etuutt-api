<?php

namespace App\Entity;

use App\Entity\Traits\SoftDeletableTrait;
use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Controller\SoftDeleteController;
use App\Repository\AssoRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *  The main entity that represents all Assos.
 *
 * @ORM\Entity(repositoryClass=AssoRepository::class)
 * @ORM\Table(name="assos")
 * @ORM\HasLifecycleCallbacks
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
class Asso
{

    use SoftDeletableTrait;
    use TimestampsTrait;
    use UUIDTrait;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     *
     * @Assert\Uuid
     */
    #[Groups([
        'asso:read:one',
        'asso:read:some',
    ])]
    private $id;

    /**
     * The login used by the CAS.
     *
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=50)
     * @Assert\Regex("/^[a-z_0-9]{1,50}$/")
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=100)
     */
    #[Groups([
        'asso:read:one',
        'asso:read:some',
    ])]
    private $name;

    /**
     * The Translation object that contains the translation of the short description.
     *
     * @ORM\ManyToOne(targetEntity=Translation::class, cascade={"persist", "remove"})
     */
    #[SerializedName('descriptionShort')]
    #[Groups([
        'asso:read:some',
    ])]
    private $descriptionShortTranslation;

    /**
     * The Translation object that contains the translation of the complete description.
     *
     * @ORM\ManyToOne(targetEntity=Translation::class, cascade={"persist", "remove"})
     */
    #[SerializedName('description')]
    #[Groups([
        'asso:read:one',
    ])]
    private $descriptionTranslation;

    /**
     * The email address of the association.
     *
     * @ORM\Column(type="string", length=100)
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=100)
     * @Assert\Email
     */
    #[Groups([
        'asso:read:one',
    ])]
    private $mail;

    /**
     * The phone number of the association.
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Type("string")
     * @Assert\Length(min=0, max=30)
     * @Assert\Regex("/^0[0-9]{9}$/")
     */
    #[Groups([
        'asso:read:one',
    ])]
    private $phoneNumber;

    /**
     * The website of the association. It is optional.
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Type("string")
     * @Assert\Length(min=0, max=100)
     * @Assert\Url
     */
    #[Groups([
        'asso:read:one',
    ])]
    private $website;

    /**
     * Link to the logo of the association. It is optional.
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Type("string")
     * @Assert\Length(min=0, max=100)
     */
    #[Groups([
        'asso:read:some',
        'asso:read:one',
    ])]
    private $logo;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     */
    #[Groups([
        'asso:read:one',
    ])]
    private $createdAt;

    /**
     * The relation to all Keywords of this Asso.
     *
     * @ORM\ManyToMany(targetEntity=AssoKeyword::class, inversedBy="assos")
     * @ORM\JoinTable(
     *     name="assos_keywords",
     *     joinColumns={@ORM\JoinColumn(name="asso_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="keyword", referencedColumnName="name")}
     * )
     */
    #[Groups([
        'asso:read:one',
    ])]
    private $keywords;

    /**
     * The relation to all assoMessages sent by this Asso.
     *
     * @ORM\OneToMany(targetEntity=AssoMessage::class, mappedBy="asso", orphanRemoval=true)
     */
    private $assoMessages;

    /**
     * The relation to all events in which this Asso participate.
     *
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="assos")
     */
    #[Groups([
        'asso:read:one',
    ])]
    private $events;

    /**
     * The relation to all Groups of this Asso.
     *
     * @ORM\OneToMany(targetEntity=Group::class, mappedBy="asso")
     */
    private $groups;

    /**
     * The relation to all AssoMemberships of this Asso.
     *
     * @ORM\OneToMany(targetEntity=AssoMembership::class, mappedBy="asso", orphanRemoval=true)
     */
    #[Groups([
        'asso:read:one',
    ])]
    private $assoMemberships;

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
        if ($this->assoMessages->removeElement($assoMessage)) {
            // set the owning side to null (unless already changed)
            if ($assoMessage->getAsso() === $this) {
                $assoMessage->setAsso(null);
            }
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
        if ($this->groups->removeElement($group)) {
            // set the owning side to null (unless already changed)
            if ($group->getAsso() === $this) {
                $group->setAsso(null);
            }
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
        if ($this->assoMemberships->removeElement($assoMember)) {
            // set the owning side to null (unless already changed)
            if ($assoMember->getAsso() === $this) {
                $assoMember->setAsso(null);
            }
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
