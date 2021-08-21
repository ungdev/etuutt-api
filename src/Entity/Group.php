<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\SoftDeleteController;
use App\Repository\GroupRepository;
use App\Util\Slug;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Group of User for friends, Course... Only user can see it, only god can judge me.
 *
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 * @ORM\Table(name="groups")
 * @ORM\EntityListeners({"App\Doctrine\GroupSetAdminAndMemberListener"})
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
            'post' => [
                'denormalization_context' => [
                    'groups' => ['group:write:create'],
                ],
            ],
        ],
        itemOperations: [
            'get' => [
                'normalization_context' => [
                    'groups' => ['group:read:one'],
                ],
            ],
            'delete' => [
                'controller' => SoftDeleteController::class,
                'security' => "is_granted('delete', object)",
            ],
            'patch' => [
                'denormalization_context' => [
                    'groups' => ['group:write:update'],
                ],
                'security' => "is_granted('patch', object)",
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
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     *
     * @Assert\Uuid()
     */
    #[
        ApiProperty(
            identifier: false
        ),
        Groups([
            'group:read:one',
            'group:read:some',
        ])
    ]
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     */
    #[Groups([
        'group:read:one',
        'group:read:some',
        'group:write:create',
    ])]
    private $name;

    /**
     * The Translation object that contains the translation of the description.
     *
     * @ORM\ManyToOne(targetEntity=Translation::class, cascade={"persist", "remove"})
     */
    #[Groups([
        'group:read:one',
        'group:read:some',
        'group:write:update',
        'group:write:create',
    ]),
    SerializedName('description')]
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
    #[
        ApiProperty(
            identifier: true
        ),
        Groups([
            'group:read:one',
            'group:read:some',
        ])
    ]
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
        'group:write:update',
        'group:write:create',
    ])]
    private $avatar;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type("bool")
     */
    #[Groups([
        'group:read:one',
        'group:write:update',
        'group:write:create',
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
        'group:write:update',
    ])]
    private $members;

    /**
     * The relation that allow to add many admins to this group. Admins of a group can update and delete it.
     *
     * @ORM\ManyToMany(targetEntity=User::class)
     * @ORM\JoinTable(
     *     name="users_groups_admins",
     *     joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="admin_id", referencedColumnName="id")}
     * )
     */
    #[Groups([
        'group:write:update',
        'group:write:create',
    ])]
    private $admins;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups([
        'group:read:one',
        'group:read:some',
    ])]
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups([
        'group:read:one',
    ])]
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    public function __construct()
    {
        $this->setDescriptionTranslation(new Translation());
        $this->setCreatedAt(new DateTime());
        $this->setUpdatedAt(new DateTime());

        $this->members = new ArrayCollection();
        $this->admins = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * This setter set the slug too with the name given.
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        //  We set the slug
        $this->setSlug(Slug::slugify($name));

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

    /**
     * @return Collection|User[]
     */
    public function getAdmins(): Collection
    {
        return $this->admins;
    }

    /**
     * This method add the user passed as argument to the admins of the group. If the user is not in the group, the method add him.
     */
    public function addAdmin(User $user): self
    {
        if (!$this->members->contains($user)) {
            $this->members[] = $user;
        }
        if (!$this->admins->contains($user)) {
            $this->admins[] = $user;
        }

        return $this;
    }

    public function removeAdmin(User $user): self
    {
        $this->admins->removeElement($user);

        return $this;
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
