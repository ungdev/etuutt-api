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
     * @ORM\ManyToMany(targetEntity=AssoMembershipRole::class)
     * @ORM\JoinTable(
     *     name="event_privacies_allowed_roles",
     *     joinColumns={@ORM\JoinColumn(name="event_privacy_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role", referencedColumnName="name")}
     * )
     */
    private $allowedRoles;

    /**
     * @ORM\Column(type="boolean")
     */
    private $areMembersAllowed;

    public function __construct()
    {
        $this->allowedRoles = new ArrayCollection();
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
     * @return AssoMembershipRole[]|Collection
     */
    public function getAllowedRoles(): Collection
    {
        return $this->allowedRoles;
    }

    public function addAllowedRole(AssoMembershipRole $allowedRole): self
    {
        if (!$this->allowedRoles->contains($allowedRole)) {
            $this->allowedRoles[] = $allowedRole;
        }

        return $this;
    }

    public function removeAllowedRole(AssoMembershipRole $allowedRole): self
    {
        $this->allowedRoles->removeElement($allowedRole);

        return $this;
    }

    public function getAreMembersAllowed(): ?bool
    {
        return $this->areMembersAllowed;
    }

    public function setAreMembersAllowed(bool $areMembersAllowed): self
    {
        $this->areMembersAllowed = $areMembersAllowed;

        return $this;
    }
}
