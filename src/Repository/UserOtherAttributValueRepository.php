<?php

namespace App\Repository;

use App\Entity\UserOtherAttributValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserOtherAttributValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserOtherAttributValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserOtherAttributValue[]    findAll()
 * @method UserOtherAttributValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserOtherAttributValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserOtherAttributValue::class);
    }

    // /**
    //  * @return UserOtherAttributValue[] Returns an array of UserOtherAttributValue objects
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
    public function findOneBySomeField($value): ?UserOtherAttributValue
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
