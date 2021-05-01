<?php

namespace App\Repository;

use App\Entity\UEAnnalReportReason;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UEAnnalReportReason find($id, $lockMode = null, $lockVersion = null)
 * @method null|UEAnnalReportReason findOneBy(array $criteria, array $orderBy = null)
 * @method UEAnnalReportReason[]    findAll()
 * @method UEAnnalReportReason[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UEAnnalReportReasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UEAnnalReportReason::class);
    }

    // /**
    //  * @return UEAnnalReportReason[] Returns an array of UEAnnalReportReason objects
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
    public function findOneBySomeField($value): ?UEAnnalReportReason
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
