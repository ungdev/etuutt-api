<?php

namespace App\Entity;

use App\Entity\Traits\UUIDTrait;
use App\Repository\UserEtuUTTTeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that stores which User has been member of this project, what he or she has done, and when.
 *
 * @ORM\Entity(repositoryClass=UserEtuUTTTeamRepository::class)
 * @ORM\Table(name="user_etuutt_team")
 */
class UserEtuUTTTeam
{
    use UUIDTrait;

    /**
     * The relation to the User.
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * The relation to the Semesters during which the User has worked on this project.
     *
     * @ORM\ManyToMany(targetEntity=Semester::class)
     * @ORM\JoinTable(
     *     name="user_etuutt_team_semesters",
     *     joinColumns={@ORM\JoinColumn(name="user_etuutt_team_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="semester_code", referencedColumnName="code")}
     * )
     */
    private $semester;

    /**
     * The description of what the User has done.
     *
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Type("string")
     */
    private $role;

    public function __construct()
    {
        $this->semester = new ArrayCollection();
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
