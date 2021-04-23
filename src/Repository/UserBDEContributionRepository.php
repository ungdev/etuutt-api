<?php

namespace App\Repository;

use App\Entity\UserBDEContribution;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UserBDEContribution find($id, $lockMode = null, $lockVersion = null)
 * @method null|UserBDEContribution findOneBy(array $criteria, array $orderBy = null)
 * @method UserBDEContribution[]    findAll()
 * @method UserBDEContribution[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBDEContributionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBDEContribution::class);
    }

    // /**
    //  * @return UserBDEContribution[] Returns an array of UserBDEContribution objects
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
    public function findOneBySomeField($value): ?UserBDEContribution
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
