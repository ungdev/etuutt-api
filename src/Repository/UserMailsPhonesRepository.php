<?php

namespace App\Repository;

use App\Entity\UserMailsPhones;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserMailsPhones|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserMailsPhones|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserMailsPhones[]    findAll()
 * @method UserMailsPhones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserMailsPhonesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserMailsPhones::class);
    }

    // /**
    //  * @return UserMailsPhones[] Returns an array of UserMailsPhones objects
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
    public function findOneBySomeField($value): ?UserMailsPhones
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
