<?php

namespace App\Repository;

use App\Entity\UserOtherAttribut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UserOtherAttribut find($id, $lockMode = null, $lockVersion = null)
 * @method null|UserOtherAttribut findOneBy(array $criteria, array $orderBy = null)
 * @method UserOtherAttribut[]    findAll()
 * @method UserOtherAttribut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserOtherAttributRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserOtherAttribut::class);
    }

    // /**
    //  * @return UserOtherAttribut[] Returns an array of UserOtherAttribut objects
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
    public function findOneBySomeField($value): ?UserOtherAttribut
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
