<?php

namespace App\Repository;

use App\Entity\UEStarCriterion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UEStarCriterion find($id, $lockMode = null, $lockVersion = null)
 * @method null|UEStarCriterion findOneBy(array $criteria, array $orderBy = null)
 * @method UEStarCriterion[]    findAll()
 * @method UEStarCriterion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UEStarCriterionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UEStarCriterion::class);
    }

    // /**
    //  * @return UEStarCriterion[] Returns an array of UEStarCriterion objects
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
    public function findOneBySomeField($value): ?UEStarCriterion
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
