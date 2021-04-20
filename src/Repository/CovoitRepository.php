<?php

namespace App\Repository;

use App\Entity\Covoit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Covoit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Covoit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Covoit[]    findAll()
 * @method Covoit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CovoitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Covoit::class);
    }

    // /**
    //  * @return Covoit[] Returns an array of Covoit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Covoit
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
