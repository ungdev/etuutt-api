<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\EventCategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventCategoryRepository::class)]
#[ORM\Table(name: 'event_categories')]
class EventCategory
{
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private $id;

    /**
     * The name of the event category.
     */
    #[ORM\Column(type: Types::STRING, length: 100, unique: true)]
    private ?string $name = null;

    /**
     * All events related to that category.
     */
    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'categories')]
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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
            $event->addCategory($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            $event->removeCategory($this);
        }

        return $this;
    }
}
