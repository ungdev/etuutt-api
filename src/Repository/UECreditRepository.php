<?php

namespace App\Repository;

use App\Entity\UECredit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UECredit find($id, $lockMode = null, $lockVersion = null)
 * @method null|UECredit findOneBy(array $criteria, array $orderBy = null)
 * @method UECredit[]    findAll()
 * @method UECredit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UECreditRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UECredit::class);
    }

    // /**
    //  * @return UECredit[] Returns an array of UECredit objects
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
    public function findOneBySomeField($value): ?UECredit
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
