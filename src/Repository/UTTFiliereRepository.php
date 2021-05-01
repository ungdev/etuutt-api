<?php

namespace App\Repository;

use App\Entity\UTTFiliere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UTTFiliere find($id, $lockMode = null, $lockVersion = null)
 * @method null|UTTFiliere findOneBy(array $criteria, array $orderBy = null)
 * @method UTTFiliere[]    findAll()
 * @method UTTFiliere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UTTFiliereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UTTFiliere::class);
    }

    // /**
    //  * @return UTTFiliere[] Returns an array of UTTFiliere objects
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
    public function findOneBySomeField($value): ?UTTFiliere
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
