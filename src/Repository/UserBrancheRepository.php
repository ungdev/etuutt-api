<?php

namespace App\Repository;

use App\Entity\UserBranche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserBranche|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBranche|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBranche[]    findAll()
 * @method UserBranche[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBrancheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBranche::class);
    }

    // /**
    //  * @return UserBranche[] Returns an array of UserBranche objects
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
    public function findOneBySomeField($value): ?UserBranche
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
