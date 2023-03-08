<?php

namespace App\Entity;

use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use App\Repository\UECommentReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * The entity that is created whan a User report a Comment.
 */
#[ORM\Entity(repositoryClass: UECommentReportRepository::class)]
#[ORM\Table(name: 'ue_comment_report')]
#[ORM\HasLifecycleCallbacks]
class UECommentReport
{
    use TimestampsTrait;
    use UUIDTrait;

    /**
     * The relation to the reported Comment.
     */
    #[ORM\ManyToOne(targetEntity: UEComment::class, inversedBy: 'reports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UEComment $comment = null;

    /**
     * The relation to the User reporting the Comment.
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * The relation to the reason of reporting.
     */
    #[ORM\ManyToOne(targetEntity: UECommentReportReason::class)]
    #[ORM\JoinColumn(name: 'reason_name', referencedColumnName: 'name')]
    private ?UECommentReportReason $reason = null;

    /**
     * The text typed by the reporter to describe the reason.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $body = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
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
}
