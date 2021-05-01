<?php

namespace App\Repository;

use App\Entity\AssoMember;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|AssoMember find($id, $lockMode = null, $lockVersion = null)
 * @method null|AssoMember findOneBy(array $criteria, array $orderBy = null)
 * @method AssoMember[]    findAll()
 * @method AssoMember[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssoMemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssoMember::class);
    }

    // /**
    //  * @return AssoMember[] Returns an array of AssoMember objects
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
    public function findOneBySomeField($value): ?AssoMember
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
