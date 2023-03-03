<?php

namespace App\Entity;

use DateTimeInterface;
use DateTime;
use App\Repository\UECommentReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that is created whan a User report a Comment.
 */
#[ORM\Entity(repositoryClass: UECommentReportRepository::class)]
#[ORM\Table(name: 'ue_comment_report')]
class UECommentReport
{
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

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
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $body = null;

    #[Assert\Type('\DateTimeInterface')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $createdAt;

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

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
