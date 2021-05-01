<?php

namespace App\Repository;

use App\Entity\AssoKeyword;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|AssoKeyword find($id, $lockMode = null, $lockVersion = null)
 * @method null|AssoKeyword findOneBy(array $criteria, array $orderBy = null)
 * @method AssoKeyword[]    findAll()
 * @method AssoKeyword[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssoKeywordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssoKeyword::class);
    }

    // /**
    //  * @return AssoKeyword[] Returns an array of AssoKeyword objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AssoKeyword
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
