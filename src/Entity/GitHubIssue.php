<?php

namespace App\Entity;

use DateTimeInterface;
use DateTime;
use App\Repository\GitHubIssueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that stores the User that has created an issue on GitHub from etu.utt.fr.
 */
#[ORM\Entity(repositoryClass: GitHubIssueRepository::class)]
#[ORM\Table(name: 'github_issues')]
class GitHubIssue
{
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    /**
     * The relation that allow to each User to add own a GitHubIssue.
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * The id of the GitHub issue.
     */
    #[Assert\Type(type: 'integer')]
    #[Assert\Positive]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $gitHubIssueId = null;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGitHubIssueId(): ?int
    {
        return $this->gitHubIssueId;
    }

    public function setGitHubIssueId(int $gitHubIssueId): self
    {
        $this->gitHubIssueId = $gitHubIssueId;

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
