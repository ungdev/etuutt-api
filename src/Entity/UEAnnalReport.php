<?php

namespace App\Entity;

use App\Entity\Traits\TimestampsTrait;
use App\Repository\UEAnnalReportRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity that is created whan a User report a UEAnnal.
 *
 * @ORM\Entity(repositoryClass=UEAnnalReportRepository::class)
 * @ORM\Table(name="ue_annal_report")
 * @ORM\HasLifecycleCallbacks
 */
class UEAnnalReport
{
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
     * The relation to the reported UEAnnal.
     *
     * @ORM\ManyToOne(targetEntity=UEAnnal::class, inversedBy="reports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $annal;

    /**
     * The relation to the User reporting the UEAnnal.
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * The relation to the reason of reporting.
     *
     * @ORM\ManyToOne(targetEntity=UEAnnalReportReason::class)
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

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
    }

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
}
