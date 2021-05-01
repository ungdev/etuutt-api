<?php

namespace App\Entity;

use App\Repository\UEAnnalReportReasonRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UEAnnalReportReasonRepository::class)
 * @ORM\Table(name="ue_annal_report_reasons")
 */
class UEAnnalReportReason
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Traduction::class)
     * @ORM\JoinColumn(name="description_traduction_code", referencedColumnName="code")
     */
    private $descriptionTraduction;

    public function __construct(string $name = null)
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescriptionTraduction(): ?Traduction
    {
        return $this->descriptionTraduction;
    }

    public function setDescriptionTraduction(?Traduction $descriptionTraduction): self
    {
        $this->descriptionTraduction = $descriptionTraduction;

        return $this;
    }
}
