<?php

namespace App\Repository;

use App\Entity\AssoMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AssoMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssoMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssoMessage[]    findAll()
 * @method AssoMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssoMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssoMessage::class);
    }

    // /**
    //  * @return AssoMessage[] Returns an array of AssoMessage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AssoMessage
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
