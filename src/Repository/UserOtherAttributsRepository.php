<?php

namespace App\Repository;

use App\Entity\UserOtherAttributs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UserOtherAttributs find($id, $lockMode = null, $lockVersion = null)
 * @method null|UserOtherAttributs findOneBy(array $criteria, array $orderBy = null)
 * @method UserOtherAttributs[]    findAll()
 * @method UserOtherAttributs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserOtherAttributsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserOtherAttributs::class);
    }

    // /**
    //  * @return UserOtherAttributs[] Returns an array of UserOtherAttributs objects
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
    public function findOneBySomeField($value): ?UserOtherAttributs
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
