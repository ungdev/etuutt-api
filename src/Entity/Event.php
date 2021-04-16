<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=EventRepository::class)
 * @ORM\Table(name="events")
 */
class Event
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
     * @ORM\ManyToMany(targetEntity=Asso::class, inversedBy="events")
     * @ORM\JoinTable(name="events_assos")
     */
    private $assos;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $begin;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $end;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAllDay;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity=Traduction::class)
     * @ORM\JoinColumn(name="description_traduction_code", referencedColumnName="code")
     */
    private $descriptionTraduction;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\DateTime
     */
    private $deletedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="events")
     * @ORM\JoinTable(
     *     name="events_categories",
     *     joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="category", referencedColumnName="name")}
     * )
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=EventAnswer::class, mappedBy="event", orphanRemoval=true)
     */
    private $eventAnswers;

    public function __construct()
    {
        $this->assos = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->eventAnswers = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return Collection|Asso[]
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBegin(): ?\DateTimeInterface
    {
        return $this->begin;
    }

    public function setBegin(\DateTimeInterface $begin): self
    {
        $this->begin = $begin;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

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

    public function getDescriptionTraduction(): ?Traduction
    {
        return $this->descriptionTraduction;
    }

    public function setDescriptionTraduction(?Traduction $descriptionTraduction): self
    {
        $this->descriptionTraduction = $descriptionTraduction;

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

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
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
}
