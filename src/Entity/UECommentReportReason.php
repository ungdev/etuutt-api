<?php

namespace App\Entity;

use App\Repository\UECommentReportReasonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The entity of a reason for reporting a Comment.
 *
 * @ORM\Entity(repositoryClass=UECommentReportReasonRepository::class)
 * @ORM\Table(name="ue_comment_report_reasons")
 */
class UECommentReportReason
{
    /**
     * The name of the report reason (e.g. "Propos injurieux").
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
     * @ORM\ManyToOne(targetEntity=Translation::class)
     * @ORM\JoinColumn(name="description_traduction_code", referencedColumnName="code")
     */
    private $descriptionTranslation;

    public function __construct(string $name = null)
    {
        $this->name = $name;
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
