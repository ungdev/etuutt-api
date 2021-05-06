<?php

namespace App\Entity;

use App\Repository\EventCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventCategoryRepository::class)
 * @ORM\Table(name="event_categories")
 */
class EventCategory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, unique=true)
     *
     * @Assert\Regex("/^[a-z ]{1,20}$/")
     */
    private $name;

    /**
     * The relation between the EventCategories and the Events it qualifies.
     *
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="categories")
     */
    private $events;

    public function __construct($name)
    {
        $this->name = $name;
        $this->events = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
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
