<?php

namespace App\Entity;

use App\Repository\UEAnnalReportReasonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity of a reason for reporting a UEAnnal.
 *
 * @ORM\Entity(repositoryClass=UEAnnalReportReasonRepository::class)
 * @ORM\Table(name="ue_annal_report_reasons")
 */
class UEAnnalReportReason
{
    /**
     * The name of the report reason (e.g. "Mauvaise UE").
     *
     * @ORM\Id
     * @ORM\Column(type="string", length=100)
     *
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=100)
     */
    private $name;

    /**
     * The Translation object that contains the translation of the description.
     *
     * @ORM\ManyToOne(targetEntity=Translation::class, cascade={"persist", "remove"})
     */
    #[SerializedName("description")]
    private $descriptionTranslation;

    public function __construct(string $name = null)
    {
        $this->name = $name;
        $this->setDescriptionTranslation(new Translation());
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescriptionTranslation(): ?Translation
    {
        return $this->descriptionTranslation;
    }

    public function setDescriptionTranslation(?Translation $descriptionTranslation): self
    {
        $this->descriptionTranslation = $descriptionTranslation;

        return $this;
    }
}
