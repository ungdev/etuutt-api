<?php

namespace App\Repository;

use App\Entity\UTTBranche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UTTBranche find($id, $lockMode = null, $lockVersion = null)
 * @method null|UTTBranche findOneBy(array $criteria, array $orderBy = null)
 * @method UTTBranche[]    findAll()
 * @method UTTBranche[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UTTBrancheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UTTBranche::class);
    }

    // /**
    //  * @return UTTBranche[] Returns an array of UTTBranche objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UTTBranche
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
