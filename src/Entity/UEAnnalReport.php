<?php

namespace App\Entity;

use App\Repository\UEAnnalReportRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UEAnnalReportRepository::class)
 * @ORM\Table(name="ue_annal_report")
 */
class UEAnnalReport
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
     * @ORM\ManyToOne(targetEntity=UEAnnal::class, inversedBy="reports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $annal;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=UEAnnalReportReason::class)
     * @ORM\JoinColumn(name="reason_name", referencedColumnName="name")
     */
    private $reason;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $body;

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

    public function getAnnal(): ?UEAnnal
    {
        return $this->annal;
    }

    public function setAnnal(?UEAnnal $annal): self
    {
        $this->annal = $annal;

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

    public function getReason(): ?UEAnnalReportReason
    {
        return $this->reason;
    }

    public function setReason(?UEAnnalReportReason $reason): self
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
