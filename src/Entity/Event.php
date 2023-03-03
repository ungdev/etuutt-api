<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Controller\SoftDeleteController;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

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
            ),
        ],
        normalizationContext: [
            'skip_null_values' => false,
        ],
        paginationItemsPerPage: 10,
        security: "is_granted('ROLE_USER')",
    )
]
#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\Table(name: 'events')]
class Event
{
    #[Groups([
        'event:read:some',
        'event:read:one',
        'asso:read:one',
    ])]
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    /**
     * The relation between the Event and the Assos that organize it.
     */
    #[Groups([
        'event:read:one',
    ])]
    #[ORM\ManyToMany(targetEntity: Asso::class, inversedBy: 'events')]
    #[ORM\JoinTable(name: 'events_assos')]
    private Collection $assos;

    /**
     * The Translation object that contains the translation of the title of the event.
     */
    #[SerializedName('title')]
    #[Groups([
        'event:read:some',
        'event:read:one',
        'asso:read:one',
    ])]
    #[ORM\ManyToOne(targetEntity: Translation::class, cascade: ['persist', 'remove'])]
    private ?Translation $titleTranslation = null;

    /**
     * The starting date of the event.
     */
    #[Groups([
        'event:read:one',
    ])]
    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startAt = null;

    /**
     * The ending date of the event.
     */
    #[Groups([
        'event:read:one',
    ])]
    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endAt = null;

    /**
     * A boolean telling whether the event is from morning to evening or not.
     */
    #[Groups([
        'event:read:one',
    ])]
    #[Assert\Type('bool')]
    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $isAllDay = null;

    /**
     * The location of the event. It is optional.
     */
    #[Groups([
        'event:read:one',
    ])]
    #[Assert\Type('string')]
    #[Assert\Length(min: 0, max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $location = null;

    /**
     * The Translation object that contains the translation of the description.
     */
    #[Groups([
        'event:read:one',
    ])]
    #[SerializedName('description')]
    #[ORM\ManyToOne(targetEntity: Translation::class, cascade: ['persist', 'remove'])]
    private ?Translation $descriptionTranslation = null;

    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $updatedAt;

    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $deletedAt = null;

    /**
     * The relation to the EventCategory the Event is classified as.
     */
    #[Groups([
        'event:read:one',
    ])]
    #[ORM\ManyToMany(targetEntity: EventCategory::class, inversedBy: 'events')]
    #[ORM\JoinTable(name: 'events_categories')]
    #[ORM\JoinColumn(name: 'event_id')]
    #[ORM\InverseJoinColumn(name: 'category_id', referencedColumnName: 'id')]
    private Collection $categories;

    /**
     * The relation to the EventAnswers of the Event.
     *
     * @var Collection<int, EventAnswer>|EventAnswer[]
     */
    #[Groups([
        'event:read:one',
    ])]
    #[ORM\OneToMany(targetEntity: EventAnswer::class, mappedBy: 'event', orphanRemoval: true)]
    private Collection $eventAnswers;

    /**
     * The privacy of the Event.
     */
    #[Groups([
        'event:read:one',
    ])]
    #[ORM\OneToOne(targetEntity: EventPrivacy::class, mappedBy: 'event', cascade: ['persist', 'remove'])]
    private ?EventPrivacy $eventPrivacy = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
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

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function isSoftDeleted(): bool
    {
        return null !== $this->deletedAt;
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
        // set the owning side to null (unless already changed)
        if ($this->eventAnswers->removeElement($eventAnswer) && $eventAnswer->getEvent() === $this) {
            $eventAnswer->setEvent(null);
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
