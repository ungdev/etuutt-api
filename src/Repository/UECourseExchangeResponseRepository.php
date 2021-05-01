<?php

namespace App\Repository;

use App\Entity\UECourseExchangeResponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UECourseExchangeResponse find($id, $lockMode = null, $lockVersion = null)
 * @method null|UECourseExchangeResponse findOneBy(array $criteria, array $orderBy = null)
 * @method UECourseExchangeResponse[]    findAll()
 * @method UECourseExchangeResponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UECourseExchangeResponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UECourseExchangeResponse::class);
    }

    // /**
    //  * @return UECourseExchangeResponse[] Returns an array of UECourseExchangeResponse objects
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
    public function findOneBySomeField($value): ?UECourseExchangeResponse
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
