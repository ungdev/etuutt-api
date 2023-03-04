<?php

namespace App\Entity;

use App\Entity\Traits\UUIDTrait;
use App\Repository\UserMailsPhonesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity related to a User that stores mails and phone of a User.
 */
#[ORM\Entity(repositoryClass: UserMailsPhonesRepository::class)]
#[ORM\Table(name: 'user_mails_phones')]
class UserMailsPhones
{
    use UUIDTrait;

    /**
     * The relation to the User related to this info.
     */
    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'mailsPhones', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * The UTT email address of the User. It ends by "@utt.fr".
     */
    #[Assert\Email]
    #[Assert\Regex('/^.+@utt\.fr$/')]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $mailUTT = null;

    /**
     * The personal mail fo the User. Elle ne peut pas finir par "@utt.fr".
     */
    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    #[Assert\Email]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $mailPersonal = null;

    /**
     * Relations to all groups that can access to this data.
     */
    #[ORM\ManyToMany(targetEntity: Group::class)]
    #[ORM\JoinTable(name: 'user_visibility_mail_perso')]
    #[ORM\JoinColumn(name: 'user_mails_phones_id')]
    #[ORM\InverseJoinColumn(name: 'group_id', referencedColumnName: 'id')]
    private Collection $mailPersonalVisibility;

    /**
     * The phone number of the User. It must have this form : 0647935003, +33 6 47 93 50 03, or with . and - as separator.
     */
    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    #[Assert\Regex('/^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/')]
    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private ?string $phoneNumber = null;

    /**
     * Relations to all groups that can access to this data.
     */
    #[ORM\ManyToMany(targetEntity: Group::class)]
    #[ORM\JoinTable(name: 'user_visibility_phone_number')]
    #[ORM\JoinColumn(name: 'user_mails_phones_id')]
    #[ORM\InverseJoinColumn(name: 'group_id', referencedColumnName: 'id')]
    private Collection $phoneNumberVisibility;

    public function __construct()
    {
        $this->mailPersonalVisibility = new ArrayCollection();
        $this->phoneNumberVisibility = new ArrayCollection();
    }

    public function caller($to_call, $arg): void
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

    public function getMailUTT(): ?string
    {
        return $this->mailUTT;
    }

    public function setMailUTT(string $mailUTT): self
    {
        $this->mailUTT = $mailUTT;

        return $this;
    }

    public function getMailPersonal(): ?string
    {
        return $this->mailPersonal;
    }

    public function setMailPersonal(?string $mailPersonal): self
    {
        $this->mailPersonal = $mailPersonal;

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getMailPersonalVisibility(): Collection
    {
        return $this->mailPersonalVisibility;
    }

    public function addMailPersonalVisibility(Group $mailPersonalVisibility): self
    {
        if (!$this->mailPersonalVisibility->contains($mailPersonalVisibility)) {
            $this->mailPersonalVisibility[] = $mailPersonalVisibility;
        }

        return $this;
    }

    public function removeMailPersonalVisibility(Group $mailPersonalVisibility): self
    {
        $this->mailPersonalVisibility->removeElement($mailPersonalVisibility);

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getPhoneNumberVisibility(): Collection
    {
        return $this->phoneNumberVisibility;
    }

    public function addPhoneNumberVisibility(Group $phoneNumberVisibility): self
    {
        if (!$this->phoneNumberVisibility->contains($phoneNumberVisibility)) {
            $this->phoneNumberVisibility[] = $phoneNumberVisibility;
        }

        return $this;
    }

    public function removePhoneNumberVisibility(Group $phoneNumberVisibility): self
    {
        $this->phoneNumberVisibility->removeElement($phoneNumberVisibility);

        return $this;
    }
}
