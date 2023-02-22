<?php

namespace App\Entity;

use App\Entity\Traits\SoftDeletableTrait;
use App\Entity\Traits\TimestampsTrait;
use App\Entity\Traits\UUIDTrait;
use App\Repository\CovoitMessageRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CovoitMessageRepository::class)
 * @ORM\Table(name="covoit_messages")
 * @ORM\HasLifecycleCallbacks
 */
class CovoitMessage
{
    use SoftDeletableTrait;
    use TimestampsTrait;
    use UUIDTrait;

    /**
     * The relation between the CovoitMessage and its Covoit.
     *
     * @ORM\ManyToOne(targetEntity=Covoit::class, inversedBy="covoitMessages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $covoit;

    /**
     * The author of the CovoitMessage.
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * The text of the CovoitMessage.
     *
     * @ORM\Column(type="text")
     */
    private $body;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
    }

    public function getCovoit(): ?Covoit
    {
        return $this->covoit;
    }

    public function setCovoit(?Covoit $covoit): self
    {
        $this->covoit = $covoit;

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
}
