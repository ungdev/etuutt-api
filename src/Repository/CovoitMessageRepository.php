<?php

namespace App\Repository;

use App\Entity\CovoitMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CovoitMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method CovoitMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method CovoitMessage[]    findAll()
 * @method CovoitMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CovoitMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CovoitMessage::class);
    }

    // /**
    //  * @return CovoitMessage[] Returns an array of CovoitMessage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CovoitMessage
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
