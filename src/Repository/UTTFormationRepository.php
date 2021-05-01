<?php

namespace App\Repository;

use App\Entity\UTTFormation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UTTFormation find($id, $lockMode = null, $lockVersion = null)
 * @method null|UTTFormation findOneBy(array $criteria, array $orderBy = null)
 * @method UTTFormation[]    findAll()
 * @method UTTFormation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UTTFormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UTTFormation::class);
    }

    // /**
    //  * @return UTTFormation[] Returns an array of UTTFormation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UTTFormation
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
