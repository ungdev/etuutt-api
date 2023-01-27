<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Controller\SoftDeleteController;
use App\Repository\EventRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 * @ORM\Table(name="events")
 */
#[
    ApiResource(
        shortName: 'event',
        operations: [
            new GetCollection(
                normalizationContext: ['groups' => ['event:read:some']],
            ),
            new Get(
                normalizationContext: ['groups' => ['event:read:one']],
            ),
            new Delete(
                controller: SoftDeleteController::class,
                security: "is_granted('ROLE_ADMIN')",
            ),
            new Patch(
                normalizationContext: ['groups' => ['event:read:one']],
                denormalizationContext: ['groups' => ['event:write:update']],
                security: "object == user or is_granted('ROLE_ADMIN')",
            )
        ],
        normalizationContext: [
            'skip_null_values' => false,
        ],
        paginationItemsPerPage: 10,
        security: "is_granted('ROLE_USER')",
    )
]
class Event
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     *
     * @Assert\Uuid
     */
    #[Groups([
        'event:read:some',
        'event:read:one',
        'asso:read:one',
    ])]
    private $id;

    /**
     * The relation between the Event and the Assos that organize it.
     *
     * @ORM\ManyToMany(targetEntity=Asso::class, inversedBy="events")
     * @ORM\JoinTable(name="events_assos")
     */
    #[Groups([
        'event:read:one',
    ])]
    private $assos;

    /**
     * The Translation object that contains the translation of the title of the event.
     *
     * @ORM\ManyToOne(targetEntity=Translation::class, cascade={"persist", "remove"})
     */
    #[SerializedName('title')]
    #[Groups([
        'event:read:some',
        'event:read:one',
        'asso:read:one',
    ])]
    private $titleTranslation;

    /**
     * The starting date of the event.
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\Type("\DateTimeInterface")
     */
    #[Groups([
        'event:read:one',
    ])]
    private $startAt;

    /**
     * The ending date of the event.
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\Type("\DateTimeInterface")
     */
    #[Groups([
        'event:read:one',
    ])]
    private $endAt;

    /**
     * A boolean telling whether the event is from morning to evening or not.
     *
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type("bool")
     */
    #[Groups([
        'event:read:one',
    ])]
    private $isAllDay;

    /**
     * The location of the event. It is optional.
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Type("string")
     * @Assert\Length(min=0, max=255)
     */
    #[Groups([
        'event:read:one',
    ])]
    private $location;

    /**
     * The Translation object that contains the translation of the description.
     *
     * @ORM\ManyToOne(targetEntity=Translation::class, cascade={"persist", "remove"})
     */
    #[Groups([
        'event:read:one',
    ])]
    #[SerializedName('description')]
    private $descriptionTranslation;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\Type("\DateTimeInterface")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\Type("\DateTimeInterface")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Type("\DateTimeInterface")
     */
    private $deletedAt;

    /**
     * The relation to the EventCategory the Event is classified as.
     *
     * @ORM\ManyToMany(targetEntity=EventCategory::class, inversedBy="events")
     * @ORM\JoinTable(
     *     name="events_categories",
     *     joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     * )
     */
    #[Groups([
        'event:read:one',
    ])]
    private $categories;

    /**
     * The relation to the EventAnswers of the Event.
     *
     * @ORM\OneToMany(targetEntity=EventAnswer::class, mappedBy="event", orphanRemoval=true)
     */
    #[Groups([
        'event:read:one',
    ])]
    private $eventAnswers;

    /**
     * The privacy of the Event.
     *
     * @ORM\OneToOne(targetEntity=EventPrivacy::class, mappedBy="event", cascade={"persist", "remove"})
     */
    #[Groups([
        'event:read:one',
    ])]
    private $eventPrivacy;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
        $this->setUpdatedAt(new DateTime());
        $this->setTitleTranslation(new Translation());
        $this->setDescriptionTranslation(new Translation());

        $this->assos = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->eventAnswers = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return Asso[]|Collection
     */
    public function getAssos(): Collection
    {
        return $this->assos;
    }

    public function addAsso(Asso $asso): self
    {
        if (!$this->assos->contains($asso)) {
            $this->assos[] = $asso;
        }

        return $this;
    }

    public function removeAsso(Asso $asso): self
    {
        $this->assos->removeElement($asso);

        return $this;
    }

    public function getTitleTranslation(): ?Translation
    {
        return $this->titleTranslation;
    }

    public function setTitleTranslation(?Translation $titleTranslation): self
    {
        $this->titleTranslation = $titleTranslation;

        return $this;
    }

    public function getStartAt(): ?DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getIsAllDay(): ?bool
    {
        return $this->isAllDay;
    }

    public function setIsAllDay(bool $isAllDay): self
    {
        $this->isAllDay = $isAllDay;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

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

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function isSoftDeleted(): bool
    {
        return !(null === $this->deletedAt);
    }

    /**
     * @return Collection|EventCategory[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(EventCategory $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(EventCategory $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection|EventAnswer[]
     */
    public function getEventAnswers(): Collection
    {
        return $this->eventAnswers;
    }

    public function addEventAnswer(EventAnswer $eventAnswer): self
    {
        if (!$this->eventAnswers->contains($eventAnswer)) {
            $this->eventAnswers[] = $eventAnswer;
            $eventAnswer->setEvent($this);
        }

        return $this;
    }

    public function removeEventAnswer(EventAnswer $eventAnswer): self
    {
        if ($this->eventAnswers->removeElement($eventAnswer)) {
            // set the owning side to null (unless already changed)
            if ($eventAnswer->getEvent() === $this) {
                $eventAnswer->setEvent(null);
            }
        }

        return $this;
    }

    public function getEventPrivacy(): ?EventPrivacy
    {
        return $this->eventPrivacy;
    }

    public function setEventPrivacy(EventPrivacy $eventPrivacy): self
    {
        // set the owning side of the relation if necessary
        if ($eventPrivacy->getEvent() !== $this) {
            $eventPrivacy->setEvent($this);
        }

        $this->eventPrivacy = $eventPrivacy;

        return $this;
    }
}
