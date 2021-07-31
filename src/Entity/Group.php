<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use App\Repository\GroupRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * A Group of User for friends, Course... Only user can see it, only god can judge me.
 *
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 * @ORM\Table(name="groups")
 */
#[
    ApiResource(
        shortName: 'group',
        attributes: [
            'security' => "is_granted('ROLE_USER')",
            'pagination_items_per_page' => 10,
        ],
        collectionOperations: [
            'get' => [
                'normalization_context' => [
                    'groups' => ['group:read:some'],
                ],
            ],
            'my_groups' => [
                'method' => 'GET',
                'path' => '/groups/me',
                'normalization_context' => [
                    'groups' => ['group:read:some'],
                ],
            ],
        ],
        itemOperations: [
            'get' => [
                'normalization_context' => [
                    'groups' => ['group:read:one'],
                ],
            ],
        ]
    )
]
class Group
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     *
     * @Assert\Uuid(versions={4})
     */
    #[Groups([
        'group:read:one',
        'group:read:some',
    ])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     */
    #[Groups([
        'group:read:one',
        'group:read:some',
    ])]
    private $name;

    /**
     * The Translation object that contains the translation of the description.
     *
     * @ORM\ManyToOne(targetEntity=Translation::class)
     * @ORM\JoinColumn(name="description_traduction_code", referencedColumnName="code")
     */
    #[Groups([
        'group:read:one',
        'group:read:some',
    ])]
    private $descriptionTranslation;

    /**
     * If the group is related to an Asso, this field own the relation.
     *
     * @ORM\ManyToOne(targetEntity=Asso::class, inversedBy="groups")
     */
    #[Groups([
        'group:read:one',
        'group:read:some',
    ])]
    private $asso;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     * @Assert\Regex("/^[a-z0-9]+(?:-[a-z0-9]+)*$/")
     */
    #[Groups([
        'group:read:one',
    ])]
    private $slug;
    
    /**
     * The path to the avatar of the Group.
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=255)
     */
    #[Groups([
        'group:read:one',
        'group:read:some',
    ])]
    private $avatar;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type("bool")
     */
    #[Groups([
        'group:read:one',
    ])]
    private $isVisible;

    /**
     * The relation that allow to add many Users into many Groups.
     *
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="groups")
     * @ORM\JoinTable(
     *     name="users_groups",
     *     joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="member_id", referencedColumnName="id")}
     * )
     */
    #[Groups([
        'group:read:one',
    ])]
    private $members;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    #[Groups([
        'group:read:one',
        'group:read:some',
    ])]
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    #[Groups([
        'group:read:one',
    ])]
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\DateTime
     */
    private $deletedAt;

    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescriptionTranslation(): ?Translation
    {
        return $this->descriptionTranslation;
    }

    public function setDescriptionTranslation(?Translation $descriptionTranslation): self
    {
        $this->descriptionTranslation = $descriptionTranslation;

        return $this;
    }

    public function getAsso(): ?Asso
    {
        return $this->asso;
    }

    public function setAsso(?Asso $asso): self
    {
        $this->asso = $asso;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): self
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $user): self
    {
        if (!$this->members->contains($user)) {
            $this->members[] = $user;
        }

        return $this;
    }

    public function removeMember(User $user): self
    {
        $this->members->removeElement($user);

        return $this;
    }

    #[Groups([
        'group:read:some',
    ])]
    public function getNumberOfMembers(): int
    {
        return $this->getMembers()->count();
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
