<?php

namespace App\Entity;

use App\Repository\UserMailsPhonesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserMailsPhonesRepository::class)
 * @ORM\Table(name="user_mails_phones")
 */
class UserMailsPhones
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
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="mailsPhones", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\Email
     */
    private $mailUTT;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Assert\Email
     */
    private $mailPersonnal;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class)
     * @ORM\JoinTable(
     *      name="user_visibility_mail_perso",
     *      joinColumns={@ORM\JoinColumn(name="user_mails_phones_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_name", referencedColumnName="name")}
     * )
     */
    private $mailPersonnalVisibility;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $phoneNumber;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class)
     * 
     * @ORM\JoinTable(
     *      name="user_visibility_phone_number",
     *      joinColumns={@ORM\JoinColumn(name="user_mails_phones_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_name", referencedColumnName="name")}
     * )
     */
    private $phoneNumberVisibility;

    public function __construct()
    {
        $this->mailPersonnalVisibility = new ArrayCollection();
        $this->phoneNumberVisibility = new ArrayCollection();
    }

    public function caller($to_call, $arg) {
        if (is_callable([$this, $to_call])) {
            $this->$to_call($arg);
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

    public function getMailPersonnal(): ?string
    {
        return $this->mailPersonnal;
    }

    public function setMailPersonnal(?string $mailPersonnal): self
    {
        $this->mailPersonnal = $mailPersonnal;

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getMailPersonnalVisibility(): Collection
    {
        return $this->mailPersonnalVisibility;
    }

    public function addMailPersonnalVisibility(Group $mailPersonnalVisibility): self
    {
        if (!$this->mailPersonnalVisibility->contains($mailPersonnalVisibility)) {
            $this->mailPersonnalVisibility[] = $mailPersonnalVisibility;
        }

        return $this;
    }

    public function removeMailPersonnalVisibility(Group $mailPersonnalVisibility): self
    {
        $this->mailPersonnalVisibility->removeElement($mailPersonnalVisibility);

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
