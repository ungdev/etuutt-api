<?php

namespace App\Repository;

use App\Entity\AssoMemberPermission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|AssoMemberPermission find($id, $lockMode = null, $lockVersion = null)
 * @method null|AssoMemberPermission findOneBy(array $criteria, array $orderBy = null)
 * @method AssoMemberPermission[]    findAll()
 * @method AssoMemberPermission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssoMemberPermissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssoMemberPermission::class);
    }

    // /**
    //  * @return AssoMemberPermission[] Returns an array of AssoMemberPermission objects
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
    public function findOneBySomeField($value): ?AssoMemberPermission
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
