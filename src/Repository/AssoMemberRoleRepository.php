<?php

namespace App\Repository;

use App\Entity\AssoMemberRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|AssoMemberRole find($id, $lockMode = null, $lockVersion = null)
 * @method null|AssoMemberRole findOneBy(array $criteria, array $orderBy = null)
 * @method AssoMemberRole[]    findAll()
 * @method AssoMemberRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssoMemberRoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssoMemberRole::class);
    }

    // /**
    //  * @return AssoMemberRole[] Returns an array of AssoMemberRole objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AssoMemberRole
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
