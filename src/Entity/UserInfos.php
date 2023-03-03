<?php

namespace App\Entity;

use DateTimeInterface;
use DateTime;
use App\Repository\UserInfosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity related to User that stores its Infos.
 */
#[ORM\Entity(repositoryClass: UserInfosRepository::class)]
#[ORM\Table(name: 'user_infos')]
class UserInfos
{
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    /**
     * The relation to the User which have those Infos.
     */
    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'infos', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    #[Assert\Type('string')]
    #[Assert\Length(max: 50)]
    #[Assert\Choice(['Masculin', 'Féminin', 'Autre'])]
    #[ORM\Column(type: Types::STRING, length: 50)]
    private ?string $sex = null;

    /**
     * Relations to all groups that can access to this data.
     */
    #[ORM\ManyToMany(targetEntity: Group::class)]
    #[ORM\JoinTable(name: 'user_visibility_sex')]
    #[ORM\JoinColumn(name: 'user_infos_id')]
    #[ORM\InverseJoinColumn(name: 'group_id', referencedColumnName: 'id')]
    private Collection $sexVisibility;

    #[Groups([
        'user:read:one',
    ])]
    #[Assert\Type('string')]
    #[Assert\Length(max: 50)]
    #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
    private ?string $nationality = null;

    /**
     * Relations to all groups that can access to this data.
     */
    #[ORM\ManyToMany(targetEntity: Group::class)]
    #[ORM\JoinTable(name: 'user_visibility_nationality')]
    #[ORM\JoinColumn(name: 'user_infos_id')]
    #[ORM\InverseJoinColumn(name: 'group_id', referencedColumnName: 'id')]
    private Collection $nationalityVisibility;

    #[Groups([
        'user:read:one',
    ])]
    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $birthday = null;

    /**
     * Relations to all groups that can access to this data.
     */
    #[ORM\ManyToMany(targetEntity: Group::class)]
    #[ORM\JoinTable(name: 'user_visibility_birthday')]
    #[ORM\JoinColumn(name: 'user_infos_id')]
    #[ORM\InverseJoinColumn(name: 'group_id', referencedColumnName: 'id')]
    private Collection $birthdayVisibility;

    /**
     * The path to the avatar of the User.
     */
    #[Groups([
        'user:read:one',
        'user:read:some',
    ])]
    #[Assert\Type('string')]
    #[Assert\Length(min: 1, max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $avatar = null;

    /**
     * The User's nickname.
     */
    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    #[Assert\Type('string')]
    #[Assert\Length(max: 50)]
    #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
    private ?string $nickname = null;

    /**
     * A text given by the User to explicite his or her passions.
     */
    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $passions = null;

    /**
     * The website of the User.
     */
    #[Groups([
        'user:read:one',
        'user:write:update',
    ])]
    #[Assert\Url]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $website = null;

    public function __construct()
    {
        //  Default values
        $this->setSex('Autre');
        $this->setBirthday(new DateTime());
        $this->setAvatar('/default_user_avatar.png');

        $this->sexVisibility = new ArrayCollection();
        $this->nationalityVisibility = new ArrayCollection();
        $this->birthdayVisibility = new ArrayCollection();
    }

    public function caller($to_call, $arg): void
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

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(?string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getSexVisibility(): Collection
    {
        return $this->sexVisibility;
    }

    public function addSexVisibility(Group $sexVisibility): self
    {
        if (!$this->sexVisibility->contains($sexVisibility)) {
            $this->sexVisibility[] = $sexVisibility;
        }

        return $this;
    }

    public function removeSexVisibility(Group $sexVisibility): self
    {
        $this->sexVisibility->removeElement($sexVisibility);

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getNationalityVisibility(): Collection
    {
        return $this->nationalityVisibility;
    }

    public function addNationalityVisibility(Group $nationalityVisibility): self
    {
        if (!$this->nationalityVisibility->contains($nationalityVisibility)) {
            $this->nationalityVisibility[] = $nationalityVisibility;
        }

        return $this;
    }

    public function removeNationalityVisibility(Group $nationalityVisibility): self
    {
        $this->nationalityVisibility->removeElement($nationalityVisibility);

        return $this;
    }

    public function getBirthday(): ?DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getBirthdayVisibility(): Collection
    {
        return $this->birthdayVisibility;
    }

    public function addBirthdayVisibility(Group $birthdayVisibility): self
    {
        if (!$this->birthdayVisibility->contains($birthdayVisibility)) {
            $this->birthdayVisibility[] = $birthdayVisibility;
        }

        return $this;
    }

    public function removeBirthdayVisibility(Group $birthdayVisibility): self
    {
        $this->birthdayVisibility->removeElement($birthdayVisibility);

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getPassions(): ?string
    {
        return $this->passions;
    }

    public function setPassions(?string $passions): self
    {
        $this->passions = $passions;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }
}
