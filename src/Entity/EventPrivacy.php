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
     * The relation to the Event concerned by this EventPrivacy.
     *
     * @ORM\OneToOne(targetEntity=Event::class, inversedBy="eventPrivacy", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * The relation to the Assos allowed in the Event. If no Assos are added, the Event is public.
     *
     * @ORM\ManyToMany(targetEntity=Asso::class)
     * @ORM\JoinTable(name="event_privacies_allowed_assos")
     */
    private $allowedAssos;

    /**
     * The relation to the Roles allowed in the Event. If no Roles are added, every member of the Assos are allowed.
     *
     * @ORM\ManyToMany(targetEntity=AssoMembershipRole::class)
     * @ORM\JoinTable(
     *     name="event_privacies_allowed_roles",
     *     joinColumns={@ORM\JoinColumn(name="event_privacy_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role", referencedColumnName="name")}
     * )
     */
    private $allowedRoles;

    public function __construct()
    {
        $this->allowedAssos = new ArrayCollection();
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
     * @return Asso[]|Collection
     */
    public function getAllowedAssos(): Collection
    {
        return $this->allowedAssos;
    }

    public function addAllowedAsso(Asso $allowedAsso): self
    {
        if (!$this->allowedAssos->contains($allowedAsso)) {
            $this->allowedAssos[] = $allowedAsso;
        }

        return $this;
    }

    public function removeAllowedAsso(Asso $allowedAsso): self
    {
        $this->allowedAssos->removeElement($allowedAsso);

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

    /**
     * @return bool - Return a boolean to indicate whether the Event is public or not
     */
    public function isPublic(): bool
    {
        return $this->allowedAssos->isEmpty();
    }

    /**
     * @return bool - Return a boolean to indicate whether all members are allowed at the Event
     */
    public function areAllMembersAllowed(): bool
    {
        return $this->allowedRoles->isEmpty();
    }
}
