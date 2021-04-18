<?php

namespace App\Repository;

use App\Entity\AssoGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AssoGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssoGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssoGroup[]    findAll()
 * @method AssoGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssoGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssoGroup::class);
    }

    // /**
    //  * @return AssoGroup[] Returns an array of AssoGroup objects
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
    public function findOneBySomeField($value): ?AssoGroup
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
