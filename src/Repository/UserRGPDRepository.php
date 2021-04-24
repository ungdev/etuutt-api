<?php

namespace App\Repository;

use App\Entity\UserRGPD;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UserRGPD find($id, $lockMode = null, $lockVersion = null)
 * @method null|UserRGPD findOneBy(array $criteria, array $orderBy = null)
 * @method UserRGPD[]    findAll()
 * @method UserRGPD[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRGPDRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserRGPD::class);
    }

    // /**
    //  * @return UserRGPD[] Returns an array of UserRGPD objects
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
    public function findOneBySomeField($value): ?UserRGPD
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
