<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use App\Entity\Traits\SoftDeletableTrait;
use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use App\Filter\SoftDeletedFilter;
use App\Repository\UECommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * The entity of a Comment on a UE. It allow Users to give feedback on a UE.
 */
#[ApiFilter(SoftDeletedFilter::class)]
#[ORM\Entity(repositoryClass: UECommentRepository::class)]
#[ORM\Table(name: 'ue_comments')]
#[ORM\HasLifecycleCallbacks]
class UEComment
{
    use SoftDeletableTrait;
    use TimestampsTrait;
    use UUIDTrait;

    /**
     * The relation to the UE this Commment is for.
     */
    #[ORM\ManyToOne(targetEntity: UE::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UE $UE = null;

    /**
     * The relation to the User who has created this Comment.
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    /**
     * The content of this Comment.
     */
    #[ORM\Column(type: Types::TEXT)]
    private ?string $body = null;

    /**
     * A boolean that says if the author will be display next to his Comment.
     */
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isAnonymous = false;

    /**
     * The relation to the semester in which this comment have been created.
     */
    #[ORM\ManyToOne(targetEntity: Semester::class)]
    #[ORM\JoinColumn(name: 'semester_code', referencedColumnName: 'code')]
    private ?Semester $semester = null;

    /**
     * The relation to all UECommentReply that are answering to this Comment.
     *
     * @var Collection<int, UECommentReply>|UECommentReply[]
     */
    #[ORM\OneToMany(targetEntity: UECommentReply::class, mappedBy: 'comment')]
    private Collection $answers;

    /**
     * The relation to all Reports of this Comment.
     *
     * @var Collection<int, UECommentReport>|UECommentReport[]
     */
    #[ORM\OneToMany(targetEntity: UECommentReport::class, mappedBy: 'comment', orphanRemoval: true)]
    private Collection $reports;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());

        $this->answers = new ArrayCollection();
        $this->reports = new ArrayCollection();
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getIsAnonymous(): bool
    {
        return $this->isAnonymous;
    }

    public function setIsAnonymous(bool $isAnonymous): self
    {
        $this->isAnonymous = $isAnonymous;

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

    /**
     * @return Collection|UECommentReply[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(UECommentReply $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setComment($this);
        }

        return $this;
    }

    public function removeAnswer(UECommentReply $answer): self
    {
        // set the owning side to null (unless already changed)
        if ($this->answers->removeElement($answer) && $answer->getComment() === $this) {
            $answer->setComment(null);
        }

        return $this;
    }

    /**
     * @return Collection|UECommentReport[]
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(UECommentReport $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports[] = $report;
            $report->setComment($this);
        }

        return $this;
    }

    public function removeReport(UECommentReport $report): self
    {
        // set the owning side to null (unless already changed)
        if ($this->reports->removeElement($report) && $report->getComment() === $this) {
            $report->setComment(null);
        }

        return $this;
    }
}
