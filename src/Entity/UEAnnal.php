<?php

namespace App\Entity;

use DateTimeInterface;
use DateTime;
use App\Repository\UEAnnalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UEAnnalRepository::class)]
#[ORM\Table(name: 'ue_annals')]
class UEAnnal
{
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    /**
     * The relation to the UE of this UEAnnal.
     */
    #[ORM\ManyToOne(targetEntity: UE::class, inversedBy: 'annals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UE $UE = null;

    /**
     * The relation to the User that sent this UEAnnal.
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $sender = null;

    /**
     * The relation to the Semester during which the UEAnnal was an exam.
     */
    #[ORM\ManyToOne(targetEntity: Semester::class)]
    #[ORM\JoinColumn(name: 'semester_code', referencedColumnName: 'code')]
    private ?Semester $semester = null;

    /**
     * A relation to the type of exam that this UEAnnal is.
     */
    #[ORM\ManyToOne(targetEntity: UEAnnalType::class)]
    #[ORM\JoinColumn(name: 'type_name', referencedColumnName: 'name')]
    private ?UEAnnalType $type = null;

    /**
     * The path to the file.
     */
    #[Assert\Type('string')]
    #[Assert\Length(min: 1, max: 255)]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $filename = null;

    /**
     * The relation to the User who has validated this UEAnnal.
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $validatedBy = null;

    /**
     * The relation to the potentials Reports of this UEAnnal by Users.
     *
     * @var Collection<int, UEAnnalReport>|UEAnnalReport[]
     */
    #[ORM\OneToMany(targetEntity: UEAnnalReport::class, mappedBy: 'annal', orphanRemoval: true)]
    private Collection $reports;

    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $createdAt;

    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $deletedAt = null;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());

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
        // set the owning side to null (unless already changed)
        if ($this->reports->removeElement($report) && $report->getAnnal() === $this) {
            $report->setAnnal(null);
        }

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

    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTime $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function isSoftDeleted(): bool
    {
        return null !== $this->deletedAt;
    }
}
