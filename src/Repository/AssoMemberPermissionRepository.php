<?php

namespace App\Repository;

use App\Entity\AssoMembershipPermission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|AssoMembershipPermission find($id, $lockMode = null, $lockVersion = null)
 * @method null|AssoMembershipPermission findOneBy(array $criteria, array $orderBy = null)
 * @method AssoMembershipPermission[]    findAll()
 * @method AssoMembershipPermission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssoMemberPermissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssoMembershipPermission::class);
    }

    // /**
    //  * @return AssoMembershipPermission[] Returns an array of AssoMembershipPermission objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AssoMembershipPermission
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
