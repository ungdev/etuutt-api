<?php

namespace App\Entity;

use App\Repository\SemesterRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The Semester entity. It uniforms the way we deal with semesters.
 *
 * @ORM\Entity(repositoryClass=SemesterRepository::class)
 * @ORM\Table(name="semesters")
 */
class Semester
{
    /**
     * The starting date of the Semester.
     *
     * @ORM\Column(type="date")
     */
    #[Assert\Date]
    private ?\DateTimeInterface $start = null;

    /**
     * The ending date of the Semester.
     *
     * @ORM\Column(type="date")
     */
    #[Assert\Date]
    private ?\DateTimeInterface $end = null;

    public function __construct(
        /**
         * @ORM\Id
         * @ORM\Column(type="string", length=10)
         */
        #[Assert\Type('string')]
        #[Assert\Length(max: 10)]
        #[Assert\Regex('/^(A|P)\d{2}$/')]
        private ?string $code = null
    )
    {
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }
}
