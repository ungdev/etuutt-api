<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserEtuUTTTeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass=UserEtuUTTTeamRepository::class)
 * @ORM\Table(name="user_etuutt_team")
 */
class UserEtuUTTTeam
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
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Semester::class)
     * @ORM\JoinTable(
     *     name="user_etuutt_team_semesters",
     *     joinColumns={@ORM\JoinColumn(name="user_etuutt_team_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="semester_code", referencedColumnName="code")}
     * )
     */
    private $semester;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $role;

    public function __construct()
    {
        $this->semester = new ArrayCollection();
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

    /**
     * @return Collection|Semester[]
     */
    public function getSemester(): Collection
    {
        return $this->semester;
    }

    public function addSemester(Semester $semester): self
    {
        if (!$this->semester->contains($semester)) {
            $this->semester[] = $semester;
        }

        return $this;
    }

    public function removeSemester(Semester $semester): self
    {
        $this->semester->removeElement($semester);

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }
}
