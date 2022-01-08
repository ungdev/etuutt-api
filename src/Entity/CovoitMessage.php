<?php

namespace App\Entity;

use App\Entity\Traits\SoftDeletableTrait;
use App\Entity\Traits\TimestampsTrait;
use App\Repository\CovoitMessageRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CovoitMessageRepository::class)
 * @ORM\Table(name="covoit_messages")
 */
class CovoitMessage
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
        $this->setCreatedAt(new DateTime());
    }

    public function getId(): ?Uuid
    {
        return $this->id;
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
