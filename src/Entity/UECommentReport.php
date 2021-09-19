<?php

namespace App\Entity;

use App\Repository\UECommentReportRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that is created whan a User report a Comment.
 *
 * @ORM\Entity(repositoryClass=UECommentReportRepository::class)
 * @ORM\Table(name="ue_comment_report")
 */
class UECommentReport
{
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
     * The relation to the reported Comment.
     *
     * @ORM\ManyToOne(targetEntity=UEComment::class, inversedBy="reports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $comment;

    /**
     * The relation to the User reporting the Comment.
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * The relation to the reason of reporting.
     *
     * @ORM\ManyToOne(targetEntity=UECommentReportReason::class)
     * @ORM\JoinColumn(name="reason_name", referencedColumnName="name")
     */
    private $reason;

    /**
     * The text typed by the reporter to describe the reason.
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Assert\Type("string")
     */
    private $body;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\Type("\DateTimeInterface")
     */
    private $createdAt;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getComment(): ?UEComment
    {
        return $this->comment;
    }

    public function setComment(?UEComment $comment): self
    {
        $this->comment = $comment;

        return $this;
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

    public function getReason(): ?UECommentReportReason
    {
        return $this->reason;
    }

    public function setReason(?UECommentReportReason $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

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
