<?php

namespace App\Entity;

use App\Repository\UserMailsPhonesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity related to a User that stores mails and phone of a User.
 *
 * @ORM\Entity(repositoryClass=UserMailsPhonesRepository::class)
 * @ORM\Table(name="user_mails_phones")
 */
class UserMailsPhones
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     *
     * @Assert\Uuid(versions={4})
     */
    private $id;

    /**
     * The relation to the User related to this info.
     *
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="mailsPhones", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * The UTT email address of the User. It ends by "@utt.fr".
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Email
     * @Assert\Regex("/^.+@utt\.fr$/")
     */
    private $mailUTT;

    /**
     * The personal mail fo the User. Elle ne peut pas finir par "@utt.fr".
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Email
     * @Assert\Regex("/^.+[^@utt\.fr]$/")
     */
    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    private $mailPersonal;

    /**
     * Relations to all groups that can access to this data.
     *
     * @ORM\ManyToMany(targetEntity=Group::class)
     * @ORM\JoinTable(
     *     name="user_visibility_mail_perso",
     *     joinColumns={@ORM\JoinColumn(name="user_mails_phones_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    private $mailPersonalVisibility;

    /**
     * The phone number of the User. It can take the following forms : +919367788755, 8989829304, +16308520397, 786-307-3615.
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     *
     * @Assert\Regex("/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/")
     */
    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    private $phoneNumber;

    /**
     * Relations to all groups that can access to this data.
     *
     * @ORM\ManyToMany(targetEntity=Group::class)
     * @ORM\JoinTable(
     *     name="user_visibility_phone_number",
     *     joinColumns={@ORM\JoinColumn(name="user_mails_phones_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    private $phoneNumberVisibility;

    public function __construct()
    {
        $this->mailPersonalVisibility = new ArrayCollection();
        $this->phoneNumberVisibility = new ArrayCollection();
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
