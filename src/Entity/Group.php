<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\SoftDeleteController;
use App\DataProvider\MyGroupsCollectionDataProvider;
use App\Doctrine\GroupSetAdminAndMemberListener;
use App\Entity\Traits\SoftDeletableTrait;
use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use App\Filter\SoftDeletedFilter;
use App\Filter\VisibleGroupFilter;
use App\Repository\GroupRepository;
use App\Util\Slug;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Group of User for friends, Course... Only user can see it, only god can judge me.
 */
#[
    ApiResource(
        shortName: 'group',
        operations: [
            new GetCollection(
                normalizationContext: ['groups' => ['group:read:some'], 'skip_null_values' => false],
            ),
            new GetCollection(
                uriTemplate: '/groups/me',
                normalizationContext: ['groups' => ['group:read:some'], 'skip_null_values' => false],
                provider: MyGroupsCollectionDataProvider::class,
            ),
            new Post(
                normalizationContext: ['groups' => ['group:read:one'], 'skip_null_values' => false],
                denormalizationContext: ['groups' => ['group:write:create']],
            ),
            new Get(
                normalizationContext: ['groups' => ['group:read:one'], 'skip_null_values' => false],
            ),
            new Delete(
                controller: SoftDeleteController::class,
                security: "is_granted('delete', object)",
            ),
            new Patch(
                normalizationContext: ['groups' => ['group:read:one'], 'skip_null_values' => false],
                denormalizationContext: ['groups' => ['group:write:update']],
                security: "is_granted('patch', object)",
            ),
        ],
        paginationItemsPerPage: 10,
        security: "is_granted('ROLE_USER')",
    )
]
#[ApiFilter(SoftDeletedFilter::class)]
#[ApiFilter(VisibleGroupFilter::class)]
#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\EntityListeners([GroupSetAdminAndMemberListener::class])]
#[ORM\Table(name: 'groups')]
#[ORM\HasLifecycleCallbacks]
class Group
{
    use SoftDeletableTrait;
    use TimestampsTrait;
    use UUIDTrait;

    #[
        ApiProperty(
            identifier: false
        ),
        Groups([
            'group:read:one',
            'group:read:some',
        ])
    ]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private Uuid $id;

    #[Groups([
        'group:read:one',
        'group:read:some',
        'group:write:create',
    ])]
    #[Assert\Length(max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private ?string $name = null;

    /**
     * The Translation object that contains the translation of the description.
     */
    #[
        Groups([
            'group:read:one',
            'group:read:some',
            'group:write:update',
            'group:write:create',
        ]),
        SerializedName('description')
    ]
    #[ORM\ManyToOne(targetEntity: Translation::class, cascade: ['persist', 'remove'])]
    private ?Translation $descriptionTranslation = null;

    /**
     * If the group is related to an Asso, this field own the relation.
     */
    #[Groups([
        'group:read:one',
        'group:read:some',
    ])]
    #[ORM\ManyToOne(targetEntity: Asso::class, inversedBy: 'groups')]
    private ?Asso $asso = null;

    #[
        ApiProperty(
            identifier: true
        ),
        Groups([
            'group:read:one',
            'group:read:some',
        ])
    ]
    #[Assert\Length(max: 255)]
    #[Assert\Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')]
    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private ?string $slug = null;

    /**
     * The path to the avatar of the Group.
     */
    #[Groups([
        'group:read:one',
        'group:read:some',
        'group:write:update',
        'group:write:create',
    ])]
    #[Assert\Length(min: 1, max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $avatar = null;

    #[Groups([
        'group:read:one',
        'group:write:update',
        'group:write:create',
    ])]
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isVisible = true;

    /**
     * The relation that allow to add many Users into many Groups.
     */
    #[Groups([
        'group:read:one',
        'group:write:update',
    ])]
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'groups')]
    #[ORM\JoinTable(name: 'users_groups')]
    #[ORM\JoinColumn(name: 'group_id')]
    #[ORM\InverseJoinColumn(name: 'member_id', referencedColumnName: 'id')]
    private Collection $members;

    /**
     * The relation that allow to add many admins to this group. Admins of a group can update and delete it.
     */
    #[Groups([
        'group:write:update',
        'group:write:create',
    ])]
    #[ORM\ManyToMany(targetEntity: User::class)]
    #[ORM\JoinTable(name: 'users_groups_admins')]
    #[ORM\JoinColumn(name: 'group_id')]
    #[ORM\InverseJoinColumn(name: 'admin_id', referencedColumnName: 'id')]
    private Collection $admins;

    #[Groups([
        'group:read:one',
        'group:read:some',
    ])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    #[Groups([
        'group:read:one',
    ])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->setDescriptionTranslation(new Translation());
        $this->setCreatedAt(new \DateTime());

        $this->members = new ArrayCollection();
        $this->admins = new ArrayCollection();
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

    public function getIsVisible(): bool
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
}
