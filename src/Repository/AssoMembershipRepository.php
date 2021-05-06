<?php

namespace App\Repository;

use App\Entity\AssoMembership;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|AssoMembership find($id, $lockMode = null, $lockVersion = null)
 * @method null|AssoMembership findOneBy(array $criteria, array $orderBy = null)
 * @method AssoMembership[]    findAll()
 * @method AssoMembership[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssoMembershipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssoMembership::class);
    }

    // /**
    //  * @return AssoMembership[] Returns an array of AssoMembership objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AssoMembership
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
