<?php

namespace App\Entity;

use App\Repository\AssoMembershipRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AssoMembershipRepository::class)
 * @ORM\Table(name="asso_memberships")
 */
class AssoMembership
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    #[Assert\Uuid]
    private ?Uuid $id = null;

    /**
     * The relation to the User that is subscribed to an Asso.
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="assoMembership")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups([
        'asso:read:one',
    ])]
    private ?User $user = null;

    /**
     * The Asso in which the User is subscribed.
     *
     * @ORM\ManyToOne(targetEntity=Asso::class, inversedBy="assoMemberships")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Asso $asso = null;

    /**
     * The relation to the roles accorded to the User in an Asso.
     *
     * @ORM\ManyToMany(targetEntity=AssoMembershipRole::class)
     * @ORM\JoinTable(
     *     name="asso_memberships_roles",
     *     joinColumns={@ORM\JoinColumn(name="member_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role", referencedColumnName="name")}
     * )
     */
    private Collection $roles;

    /**
     * The relation to the permissions accorded to the User in an Asso.
     *
     * @ORM\ManyToMany(targetEntity=AssoMembershipPermission::class)
     * @ORM\JoinTable(
     *     name="asso_memberships_permissions",
     *     joinColumns={@ORM\JoinColumn(name="member_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="permission", referencedColumnName="name")}
     * )
     */
    private Collection $permissions;

    /**
     * @ORM\Column(type="date")
     */
    private ?\DateTimeInterface $startAt = null;

    /**
     * @ORM\Column(type="date")
     */
    private ?\DateTimeInterface $endAt = null;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Assert\Type('\DateTimeInterface')]
    private ?\DateTimeInterface $createdAt = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());

        $this->roles = new ArrayCollection();
        $this->permissions = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    /**
     * @return AssoMembershipRole[]|Collection
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(AssoMembershipRole $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(AssoMembershipRole $role): self
    {
        $this->roles->removeElement($role);

        return $this;
    }

    /**
     * @return AssoMembershipPermission[]|Collection
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function addPermission(AssoMembershipPermission $permission): self
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions[] = $permission;
        }

        return $this;
    }

    public function removePermission(AssoMembershipPermission $permission): self
    {
        $this->permissions->removeElement($permission);

        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

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
}
