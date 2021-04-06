<?php

namespace App\Repository;

use App\Entity\Semester;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Semester|null find($id, $lockMode = null, $lockVersion = null)
 * @method Semester|null findOneBy(array $criteria, array $orderBy = null)
 * @method Semester[]    findAll()
 * @method Semester[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SemesterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Semester::class);
    }

    // /**
    //  * @return Semester[] Returns an array of Semester objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

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
