<?php

namespace App\Repository;

use App\Entity\UEAnnal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UEAnnal find($id, $lockMode = null, $lockVersion = null)
 * @method null|UEAnnal findOneBy(array $criteria, array $orderBy = null)
 * @method UEAnnal[]    findAll()
 * @method UEAnnal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UEAnnalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UEAnnal::class);
    }

    // /**
    //  * @return UEAnnal[] Returns an array of UEAnnal objects
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
    public function findOneBySomeField($value): ?UEAnnal
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
