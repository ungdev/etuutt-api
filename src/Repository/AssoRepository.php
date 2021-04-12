<?php

namespace App\Repository;

use App\Entity\Asso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Asso|null find($id, $lockMode = null, $lockVersion = null)
 * @method Asso|null findOneBy(array $criteria, array $orderBy = null)
 * @method Asso[]    findAll()
 * @method Asso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Asso::class);
    }

    // /**
    //  * @return Asso[] Returns an array of Asso objects
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
    public function findOneBySomeField($value): ?Asso
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
