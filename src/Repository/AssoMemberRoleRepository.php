<?php

namespace App\Repository;

use App\Entity\AssoMembershipRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|AssoMembershipRole find($id, $lockMode = null, $lockVersion = null)
 * @method null|AssoMembershipRole findOneBy(array $criteria, array $orderBy = null)
 * @method AssoMembershipRole[]    findAll()
 * @method AssoMembershipRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssoMemberRoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssoMembershipRole::class);
    }

    // /**
    //  * @return AssoMembershipRole[] Returns an array of AssoMembershipRole objects
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
    public function findOneBySomeField($value): ?AssoMembershipRole
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
