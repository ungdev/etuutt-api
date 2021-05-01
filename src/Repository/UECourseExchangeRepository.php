<?php

namespace App\Repository;

use App\Entity\UECourseExchange;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UECourseExchange find($id, $lockMode = null, $lockVersion = null)
 * @method null|UECourseExchange findOneBy(array $criteria, array $orderBy = null)
 * @method UECourseExchange[]    findAll()
 * @method UECourseExchange[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UECourseExchangeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UECourseExchange::class);
    }

    // /**
    //  * @return UECourseExchange[] Returns an array of UECourseExchange objects
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
    public function findOneBySomeField($value): ?UECourseExchange
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
