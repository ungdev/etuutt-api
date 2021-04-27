<?php

namespace App\Repository;

use App\Entity\UserUE;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UserUE find($id, $lockMode = null, $lockVersion = null)
 * @method null|UserUE findOneBy(array $criteria, array $orderBy = null)
 * @method UserUE[]    findAll()
 * @method UserUE[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserUERepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserUE::class);
    }

    // /**
    //  * @return UserUE[] Returns an array of UserUE objects
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
    public function findOneBySomeField($value): ?UserUE
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
