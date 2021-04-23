<?php

namespace App\Entity;

use App\Repository\UserPreferenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserPreferenceRepository::class)
 * @ORM\Table(name="user_preferences")
 */
class UserPreference
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
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="preference", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $birthdayDisplayOnlyAge;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $language;

    /**
     * @ORM\Column(type="boolean")
     */
    private $wantDaymail;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class)
     * @ORM\JoinTable(
     *     name="user_visibility_schedule",
     *     joinColumns={@ORM\JoinColumn(name="user_preferences_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="group_name", referencedColumnName="name")}
     * )
     */
    private $scheduleVisibility;

    public function __construct()
    {
        $this->scheduleVisibility = new ArrayCollection();
    }

    public function caller($to_call, $arg)
    {
        if (\is_callable([$this, $to_call])) {
            $this->{$to_call}($arg);
        }
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBirthdayDisplayOnlyAge(): ?bool
    {
        return $this->birthdayDisplayOnlyAge;
    }

    public function setBirthdayDisplayOnlyAge(bool $birthdayDisplayOnlyAge): self
    {
        $this->birthdayDisplayOnlyAge = $birthdayDisplayOnlyAge;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getWantDaymail(): ?bool
    {
        return $this->wantDaymail;
    }

    public function setWantDaymail(bool $wantDaymail): self
    {
        $this->wantDaymail = $wantDaymail;

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getScheduleVisibility(): Collection
    {
        return $this->scheduleVisibility;
    }

    public function addScheduleVisibility(Group $scheduleVisibility): self
    {
        if (!$this->scheduleVisibility->contains($scheduleVisibility)) {
            $this->scheduleVisibility[] = $scheduleVisibility;
        }

        return $this;
    }

    public function removeScheduleVisibility(Group $scheduleVisibility): self
    {
        $this->scheduleVisibility->removeElement($scheduleVisibility);

        return $this;
    }
}
