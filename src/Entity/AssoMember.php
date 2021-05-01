<?php

namespace App\Entity;

use App\Repository\AssoMemberRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AssoMemberRepository::class)
 * @ORM\Table(name="asso_members")
 */
class AssoMember
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     *
     * @Assert\Uuid(versions=4)
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="assoMembers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=AssoGroup::class, inversedBy="assoMembers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $groupName;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity=AssoMemberRole::class)
     * @ORM\JoinTable(
     *     name="asso_members_roles",
     *     joinColumns={@ORM\JoinColumn(name="member_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role", referencedColumnName="name")}
     * )
     */
    private $roles;

    /**
     * @ORM\ManyToMany(targetEntity=AssoMemberPermission::class)
     * @ORM\JoinTable(
     *     name="asso_members_permissions",
     *     joinColumns={@ORM\JoinColumn(name="member_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="permission", referencedColumnName="name")}
     * )
     */
    private $permissions;

    public function __construct()
    {
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

    public function getGroupName(): ?AssoGroup
    {
        return $this->groupName;
    }

    public function setGroupName(?AssoGroup $groupName): self
    {
        $this->groupName = $groupName;

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

    /**
     * @return Collection|AssoMemberRole[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(AssoMemberRole $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(AssoMemberRole $role): self
    {
        $this->roles->removeElement($role);

        return $this;
    }

    /**
     * @return Collection|AssoMemberPermission[]
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function addPermission(AssoMemberPermission $permission): self
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions[] = $permission;
        }

        return $this;
    }

    public function removePermission(AssoMemberPermission $permission): self
    {
        $this->permissions->removeElement($permission);

        return $this;
    }
}
