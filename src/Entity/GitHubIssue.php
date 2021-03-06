<?php

namespace App\Entity;

use App\Repository\GitHubIssueRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that stores the User that has created an issue on GitHub from etu.utt.fr.
 *
 * @ORM\Entity(repositoryClass=GitHubIssueRepository::class)
 * @ORM\Table(name="github_issues")
 */
class GitHubIssue
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     *
     * @Assert\Uuid(versions=4)
     */
    private $id;

    /**
     * The relation that allow to each User to add own a GitHubIssue.
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * The id of the GitHub issue.
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\Type(type="integer")
     * @Assert\Positive
     */
    private $gitHubIssueId;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $createdAt;

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
