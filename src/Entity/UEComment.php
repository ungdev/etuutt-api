<?php

namespace App\Entity;

use App\Entity\Traits\SoftDeletableTrait;
use App\Entity\Traits\TimestampsTrait;
use App\Repository\UECommentRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity of a Comment on a UE. It allow Users to give feedback on a UE.
 *
 * @ORM\Entity(repositoryClass=UECommentRepository::class)
 * @ORM\Table(name="ue_comments")
 */
class UEComment
{
    use SoftDeletableTrait;
    use TimestampsTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     *
     * @Assert\Uuid
     */
    private $id;

    /**
     * The relation to the UE this Commment is for.
     *
     * @ORM\ManyToOne(targetEntity=UE::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $UE;

    /**
     * The relation to the User who has created this Comment.
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * The content of this Comment.
     *
     * @ORM\Column(type="text")
     *
     * @Assert\Type("string")
     */
    private $body;

    /**
     * A boolean that says if the author will be display next to his Comment.
     *
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type("bool")
     */
    private $isAnonymous;

    /**
     * The relation to the semester in which this comment have been created.
     *
     * @ORM\ManyToOne(targetEntity=Semester::class)
     * @ORM\JoinColumn(name="semester_code", referencedColumnName="code")
     */
    private $semester;

    /**
     * The relation to all UECommentReply that are answering to this Comment.
     *
     * @ORM\OneToMany(targetEntity=UECommentReply::class, mappedBy="comment")
     */
    private $answers;

    /**
     * The relation to all Reports of this Comment.
     *
     * @ORM\OneToMany(targetEntity=UECommentReport::class, mappedBy="comment", orphanRemoval=true)
     */
    private $reports;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());

        $this->answers = new ArrayCollection();
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

    public function getIsAnonymous(): ?bool
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
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getComment() === $this) {
                $answer->setComment(null);
            }
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
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getComment() === $this) {
                $report->setComment(null);
            }
        }

        return $this;
    }
}
