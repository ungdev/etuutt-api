<?php

namespace App\Repository;

use App\Entity\UECourseExchangeReply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UECourseExchangeReply find($id, $lockMode = null, $lockVersion = null)
 * @method null|UECourseExchangeReply findOneBy(array $criteria, array $orderBy = null)
 * @method UECourseExchangeReply[]    findAll()
 * @method UECourseExchangeReply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UECourseExchangeReplyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UECourseExchangeReply::class);
    }

    // /**
    //  * @return UECourseExchangeReply[] Returns an array of UECourseExchangeReply objects
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
    public function findOneBySomeField($value): ?UECourseExchangeReply
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
