<?php

namespace App\Repository;

use App\Entity\UEAnnalType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UEAnnalType find($id, $lockMode = null, $lockVersion = null)
 * @method null|UEAnnalType findOneBy(array $criteria, array $orderBy = null)
 * @method UEAnnalType[]    findAll()
 * @method UEAnnalType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UEAnnalTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UEAnnalType::class);
    }

    // /**
    //  * @return UEAnnalType[] Returns an array of UEAnnalType objects
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
    public function findOneBySomeField($value): ?UEAnnalType
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
