<?php

namespace App\Repository;

use App\Entity\UECreditCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UECreditCategory find($id, $lockMode = null, $lockVersion = null)
 * @method null|UECreditCategory findOneBy(array $criteria, array $orderBy = null)
 * @method UECreditCategory[]    findAll()
 * @method UECreditCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UECreditCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UECreditCategory::class);
    }

    // /**
    //  * @return UECreditCategory[] Returns an array of UECreditCategory objects
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
    public function findOneBySomeField($value): ?UECreditCategory
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
