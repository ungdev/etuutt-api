<?php

namespace App\Entity;

use App\Repository\UEAnnalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UEAnnalRepository::class)
 * @ORM\Table(name="ue_annals")
 */
class UEAnnal
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     *
     * @Assert\Uuid(versions={4})
     */
    private $id;

    /**
     * The relation to the UE of this UEAnnal.
     *
     * @ORM\ManyToOne(targetEntity=UE::class, inversedBy="annals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $UE;

    /**
     * The relation to the User that sent this UEAnnal.
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $sender;

    /**
     * The relation to the Semester during which the UEAnnal was an exam.
     *
     * @ORM\ManyToOne(targetEntity=Semester::class)
     * @ORM\JoinColumn(name="semester_code", referencedColumnName="code")
     */
    private $semester;

    /**
     * A relation to the type of exam that this UEAnnal is.
     *
     * @ORM\ManyToOne(targetEntity=UEAnnalType::class)
     * @ORM\JoinColumn(name="type_name", referencedColumnName="name")
     */
    private $type;

    /**
     * The path to the file.
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=255)
     */
    private $filename;

    /**
     * The relation to the User who has validated this UEAnnal.
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $validatedBy;

    /**
     * The relation to the potentials Reports of this UEAnnal by Users.
     *
     * @ORM\OneToMany(targetEntity=UEAnnalReport::class, mappedBy="annal", orphanRemoval=true)
     */
    private $reports;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\DateTime
     */
    private $deletedAt;

    public function __construct()
    {
        $this->reports = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getUE(): ?UE
    {
        return $this->UE;
    }

    public function setUE(?UE $UE): self
    {
        $this->UE = $UE;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

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

    public function getType(): ?UEAnnalType
    {
        return $this->type;
    }

    public function setType(?UEAnnalType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getValidatedBy(): ?User
    {
        return $this->validatedBy;
    }

    public function setValidatedBy(?User $validatedBy): self
    {
        $this->validatedBy = $validatedBy;

        return $this;
    }

    /**
     * @return Collection|UEAnnalReport[]
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(UEAnnalReport $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports[] = $report;
            $report->setAnnal($this);
        }

        return $this;
    }

    public function removeReport(UEAnnalReport $report): self
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getAnnal() === $this) {
                $report->setAnnal(null);
            }
        }

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
}
