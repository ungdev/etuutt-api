<?php

namespace App\Repository;

use App\Entity\UserFormation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserFormation|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserFormation|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserFormation[]    findAll()
 * @method UserFormation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserFormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFormation::class);
    }

    // /**
    //  * @return UserFormation[] Returns an array of UserFormation objects
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
    public function findOneBySomeField($value): ?UserFormation
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
