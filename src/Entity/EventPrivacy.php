<?php

namespace App\Entity;

use App\Repository\EventPrivacyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventPrivacyRepository::class)
 * @ORM\Table(name="event_privacies")
 */
class EventPrivacy
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
     * @ORM\OneToOne(targetEntity=Event::class, inversedBy="eventPrivacy", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class)
     * @ORM\JoinTable(name="events_allowed_groups")
     */
    private $allowedGroups;

    public function __construct()
    {
        $this->allowedGroups = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getAllowedGroups(): Collection
    {
        return $this->allowedGroups;
    }

    public function addAllowedGroup(Group $allowedGroup): self
    {
        if (!$this->allowedGroups->contains($allowedGroup)) {
            $this->allowedGroups[] = $allowedGroup;
        }

        return $this;
    }

    public function removeAllowedGroup(Group $allowedGroup): self
    {
        $this->allowedGroups->removeElement($allowedGroup);

        return $this;
    }
}
