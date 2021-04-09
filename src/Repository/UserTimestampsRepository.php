<?php

namespace App\Repository;

use App\Entity\UserTimestamps;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserTimestamps|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTimestamps|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTimestamps[]    findAll()
 * @method UserTimestamps[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTimestampsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTimestamps::class);
    }

    // /**
    //  * @return UserTimestamps[] Returns an array of UserTimestamps objects
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
    public function findOneBySomeField($value): ?UserTimestamps
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
