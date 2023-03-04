<?php

namespace App\Entity;

use App\Entity\Traits\UUIDTrait;
use App\Repository\UserPreferenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity related to User that stores its Preferences.
 */
#[ORM\Entity(repositoryClass: UserPreferenceRepository::class)]
#[ORM\Table(name: 'user_preferences')]
class UserPreference
{
    use UUIDTrait;

    /**
     * The relation to the User which have those Preferences.
     */
    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'preference', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * The boolean that informs us if we show or not the birthday of this User.
     */
    #[Groups([
        'user:write:update',
    ])]
    #[Assert\Type('bool')]
    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $birthdayDisplayOnlyAge = null;

    /**
     * The language prefered by the User. It follows the ISO 639-1 convention.
     */
    #[Groups([
        'user:write:update',
    ])]
    #[Assert\Type('string')]
    #[Assert\Choice(['fr', 'en', 'es', 'de', 'zh'])]
    #[ORM\Column(type: Types::STRING, length: 5)]
    private ?string $language = null;

    /**
     * The boolean that informs us if we send day mail to this User or not.
     */
    #[Groups([
        'user:write:update',
    ])]
    #[Assert\Type('bool')]
    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $wantDaymail = null;

    /**
     * The boolean that informs us if we send day notif to this User or not.
     */
    #[Groups([
        'user:write:update',
    ])]
    #[Assert\Type('bool')]
    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $wantDayNotif = null;

    /**
     * Relations to all groups that can access to this data.
     */
    #[ORM\ManyToMany(targetEntity: Group::class)]
    #[ORM\JoinTable(name: 'user_visibility_schedule')]
    #[ORM\JoinColumn(name: 'user_preferences_id')]
    #[ORM\InverseJoinColumn(name: 'group_id', referencedColumnName: 'id')]
    private Collection $scheduleVisibility;

    public function __construct()
    {
        $this->setBirthdayDisplayOnlyAge(true);
        $this->setLanguage('fr');
        $this->setWantDaymail(true);
        $this->setWantDayNotif(true);

        $this->scheduleVisibility = new ArrayCollection();
    }

    /**
     * This method permits to dynamically call visibility's getters and setters.
     */
    public function caller(mixed $to_call, mixed $arg): void
    {
        if (\is_callable([$this, $to_call])) {
            $this->{$to_call}($arg);
        }
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

    public function getWantDayNotif(): ?bool
    {
        return $this->wantDayNotif;
    }

    public function setWantDayNotif(bool $wantDayNotif): self
    {
        $this->wantDayNotif = $wantDayNotif;

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
