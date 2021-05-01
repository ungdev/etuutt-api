<?php

namespace App\Repository;

use App\Entity\UECommentReportReason;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UECommentReportReason find($id, $lockMode = null, $lockVersion = null)
 * @method null|UECommentReportReason findOneBy(array $criteria, array $orderBy = null)
 * @method UECommentReportReason[]    findAll()
 * @method UECommentReportReason[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UECommentReportReasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UECommentReportReason::class);
    }

    // /**
    //  * @return UECommentReportReason[] Returns an array of UECommentReportReason objects
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
    public function findOneBySomeField($value): ?UECommentReportReason
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
