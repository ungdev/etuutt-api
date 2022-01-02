<?php

namespace App\Repository;

use App\Entity\Semester;
use DateTime;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @method null|Semester find($id, $lockMode = null, $lockVersion = null)
 * @method null|Semester findOneBy(array $criteria, array $orderBy = null)
 * @method Semester[]    findAll()
 * @method Semester[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SemesterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Semester::class);
    }

    /**
     * @param DateTime $date The date of which we want to find the semester
     *
     * @return Semester Returns the semester in which there is the input date
     */
    public function getSemesterOfDate(DateTimeInterface $date)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.start <= :now')
            ->andWhere('s.end >= :now')
            ->setParameter('now', $date)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param Semester $semester The semester of which the next one is returned
     *
     * @return Semester Returns the next semester
     */
    public function getNextSemester(Semester $semester)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.start >= :end')
            ->setParameter('end', $semester->getEnd())
            ->orderBy('s.start', 'ASC')
            ->getQuery()
            ->getResult()[0]
        ;
    }

    /*
    public function findOneBySomeField($value): ?Semester
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
