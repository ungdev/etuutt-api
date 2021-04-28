<?php

namespace App\Repository;

use App\Entity\UEWorkTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UEWorkTime find($id, $lockMode = null, $lockVersion = null)
 * @method null|UEWorkTime findOneBy(array $criteria, array $orderBy = null)
 * @method UEWorkTime[]    findAll()
 * @method UEWorkTime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UEWorkTimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UEWorkTime::class);
    }

    // /**
    //  * @return UEWorkTime[] Returns an array of UEWorkTime objects
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
    public function findOneBySomeField($value): ?UEWorkTime
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
