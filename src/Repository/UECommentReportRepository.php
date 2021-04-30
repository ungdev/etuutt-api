<?php

namespace App\Repository;

use App\Entity\UECommentReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UECommentReport find($id, $lockMode = null, $lockVersion = null)
 * @method null|UECommentReport findOneBy(array $criteria, array $orderBy = null)
 * @method UECommentReport[]    findAll()
 * @method UECommentReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UECommentReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UECommentReport::class);
    }

    // /**
    //  * @return UECommentReport[] Returns an array of UECommentReport objects
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
    public function findOneBySomeField($value): ?UECommentReport
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
