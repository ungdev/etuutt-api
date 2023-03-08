<?php

namespace App\Entity;

use App\Repository\SemesterRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The Semester entity. It uniforms the way we deal with semesters.
 */
#[ORM\Entity(repositoryClass: SemesterRepository::class)]
#[ORM\Table(name: 'semesters')]
class Semester
{
    /**
     * The starting date of the Semester.
     */
    #[Assert\Date]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTimeInterface $start;

    /**
     * The ending date of the Semester.
     */
    #[Assert\Date]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTimeInterface $end;

    public function __construct(
        #[Assert\Length(max: 10)]
        #[Assert\Regex('/^(A|P)\d{2}$/')]
        #[ORM\Id]
        #[ORM\Column(type: Types::STRING, length: 10)]
        private readonly ?string $code = null
    ) {
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getStart(): \DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): \DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }
}
