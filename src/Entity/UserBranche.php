<?php

namespace App\Entity;

use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use App\Repository\UserBrancheRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * The entity that represents a Branch followed by a User. TC is also included.
 */
#[ORM\Entity(repositoryClass: UserBrancheRepository::class)]
#[ORM\Table(name: 'user_branches')]
#[ORM\HasLifecycleCallbacks]
class UserBranche
{
    use TimestampsTrait;
    use UUIDTrait;

    /**
     * The relation to the User.
     */
    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'branche', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * The relation to the UTTBranche.
     */
    #[Groups([
        'user:read:some',
    ])]
    #[ORM\ManyToOne(targetEntity: UTTBranche::class)]
    #[ORM\JoinColumn(name: 'branche_code', referencedColumnName: 'code')]
    private ?UTTBranche $branche = null;

    /**
     * The relation to the Filiere, if the User has one.
     */
    #[Groups([
        'user:read:some',
    ])]
    #[ORM\ManyToOne(targetEntity: UTTFiliere::class)]
    #[ORM\JoinColumn(name: 'filiere_code', referencedColumnName: 'code')]
    private ?UTTFiliere $filiere = null;

    /**
     * The number of semesters done in this UTTBranche. (e.g. 2 in "TC02").
     */
    #[Groups([
        'user:read:some',
    ])]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $semesterNumber = null;

    /**
     * The relation to the semester during which the User follows this UserBranche.
     */
    #[ORM\ManyToOne(targetEntity: Semester::class)]
    #[ORM\JoinColumn(name: 'semester_code', referencedColumnName: 'code')]
    private ?Semester $semester = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
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

    public function getUTTBranche(): ?UTTBranche
    {
        return $this->branche;
    }

    public function setUTTBranche(?UTTBranche $branche): self
    {
        $this->branche = $branche;

        return $this;
    }

    public function getUTTFiliere(): ?UTTFiliere
    {
        return $this->filiere;
    }

    public function setUTTFiliere(?UTTFiliere $filiere): self
    {
        $this->filiere = $filiere;

        return $this;
    }

    public function getSemesterNumber(): ?int
    {
        return $this->semesterNumber;
    }

    public function setSemesterNumber(int $semesterNumber): self
    {
        $this->semesterNumber = $semesterNumber;

        return $this;
    }

    public function getSemester(): ?Semester
    {
        return $this->semester;
    }

    public function setSemester(?Semester $semester): self
    {
        $this->semester = $semester;

        return $this;
    }
}
