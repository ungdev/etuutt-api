<?php

namespace App\Repository;

use App\Entity\UEInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UEInfo find($id, $lockMode = null, $lockVersion = null)
 * @method null|UEInfo findOneBy(array $criteria, array $orderBy = null)
 * @method UEInfo[]    findAll()
 * @method UEInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UEInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UEInfo::class);
    }

    // /**
    //  * @return UEInfo[] Returns an array of UEInfo objects
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
    public function findOneBySomeField($value): ?UEInfo
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
