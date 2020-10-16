<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $fullName;

    /**
     * @ORM\Column(type="text")
     */
    private $firstName;

    /**
     * @ORM\Column(type="text")
     */
    private $login;

    /**
     * @ORM\Column(type="text")
     */
    private $password;

    /**
     * @ORM\Column(type="text")
     */
    private $mail;

    /**
     * @ORM\Column(type="text")
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="text")
     */
    private $sex;

    /**
     * @ORM\Column(type="text")
     */
    private $nationality;

    /**
     * @ORM\Column(type="text")
     */
    private $address;

    /**
     * @ORM\Column(type="text")
     */
    private $postalCode;

    /**
     * @ORM\Column(type="text")
     */
    private $city;

    /**
     * @ORM\Column(type="text")
     */
    private $country;

    /**
     * @ORM\Column(type="date")
     */
    private $birthday;

    /**
     * @ORM\Column(type="text")
     */
    private $personalMail;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $surnom = [];

    /**
     * @ORM\Column(type="text")
     */
    private $facebook;

    /**
     * @ORM\Column(type="text")
     */
    private $twitter;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isStudent;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isStaffUTT;

    /**
     * @ORM\Column(type="array")
     */
    private $storeRoles = [];

    /**
     * @ORM\Column(type="text")
     */
    private $bdeMembershipStart;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $bdeMembershipEnd;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $firstLogin;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isKeepingAccout;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isDeletingEverything;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkedin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getPersonalMail(): ?string
    {
        return $this->personalMail;
    }

    public function setPersonalMail(string $personalMail): self
    {
        $this->personalMail = $personalMail;

        return $this;
    }

    public function getSurnom(): ?array
    {
        return $this->surnom;
    }

    public function setSurnom(?array $surnom): self
    {
        $this->surnom = $surnom;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getIsStudent(): ?bool
    {
        return $this->isStudent;
    }

    public function setIsStudent(bool $isStudent): self
    {
        $this->isStudent = $isStudent;

        return $this;
    }

    public function getIsStaffUTT(): ?bool
    {
        return $this->isStaffUTT;
    }

    public function setIsStaffUTT(bool $isStaffUTT): self
    {
        $this->isStaffUTT = $isStaffUTT;

        return $this;
    }

    public function getStoreRoles(): ?array
    {
        return $this->storeRoles;
    }

    public function setStoreRoles(array $storeRoles): self
    {
        $this->storeRoles = $storeRoles;

        return $this;
    }

    public function getBdeMembershipStart(): ?string
    {
        return $this->bdeMembershipStart;
    }

    public function setBdeMembershipStart(string $bdeMembershipStart): self
    {
        $this->bdeMembershipStart = $bdeMembershipStart;

        return $this;
    }

    public function getBdeMembershipEnd(): ?\DateTimeInterface
    {
        return $this->bdeMembershipEnd;
    }

    public function setBdeMembershipEnd(?\DateTimeInterface $bdeMembershipEnd): self
    {
        $this->bdeMembershipEnd = $bdeMembershipEnd;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getFirstLogin(): ?bool
    {
        return $this->firstLogin;
    }

    public function setFirstLogin(?bool $firstLogin): self
    {
        $this->firstLogin = $firstLogin;

        return $this;
    }

    public function getIsKeepingAccout(): ?bool
    {
        return $this->isKeepingAccout;
    }

    public function setIsKeepingAccout(?bool $isKeepingAccout): self
    {
        $this->isKeepingAccout = $isKeepingAccout;

        return $this;
    }

    public function getIsDeletingEverything(): ?bool
    {
        return $this->isDeletingEverything;
    }

    public function setIsDeletingEverything(?bool $isDeletingEverything): self
    {
        $this->isDeletingEverything = $isDeletingEverything;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }
}
