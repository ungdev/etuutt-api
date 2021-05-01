<?php

namespace App\Repository;

use App\Entity\UEAnnalReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UEAnnalReport find($id, $lockMode = null, $lockVersion = null)
 * @method null|UEAnnalReport findOneBy(array $criteria, array $orderBy = null)
 * @method UEAnnalReport[]    findAll()
 * @method UEAnnalReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UEAnnalReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UEAnnalReport::class);
    }

    // /**
    //  * @return UEAnnalReport[] Returns an array of UEAnnalReport objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UEAnnalReport
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
