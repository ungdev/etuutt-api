<?php

namespace App\Repository;

use App\Entity\CovoitAlert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|CovoitAlert find($id, $lockMode = null, $lockVersion = null)
 * @method null|CovoitAlert findOneBy(array $criteria, array $orderBy = null)
 * @method CovoitAlert[]    findAll()
 * @method CovoitAlert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CovoitAlertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CovoitAlert::class);
    }

    // /**
    //  * @return CovoitAlert[] Returns an array of CovoitAlert objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CovoitAlert
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
