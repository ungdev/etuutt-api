<?php

namespace App\Repository;

use App\Entity\UserAdress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserAdress|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAdress|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAdress[]    findAll()
 * @method UserAdress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAdressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAdress::class);
    }

    // /**
    //  * @return UserAdress[] Returns an array of UserAdress objects
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
    public function findOneBySomeField($value): ?UserAdress
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
