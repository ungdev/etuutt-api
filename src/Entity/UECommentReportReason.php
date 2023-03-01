<?php

namespace App\Entity;

use App\Repository\UECommentReportReasonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;
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
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=100)
     */
    private ?string $name;

    /**
     * The Translation object that contains the translation of the description.
     *
     * @ORM\ManyToOne(targetEntity=Translation::class, cascade={"persist", "remove"})
     */
    #[SerializedName('description')]
    private ?Translation $descriptionTranslation = null;

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
