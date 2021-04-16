<?php

namespace App\Repository;

use App\Entity\EventAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventAnswer[]    findAll()
 * @method EventAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventAnswer::class);
    }

    // /**
    //  * @return EventAnswer[] Returns an array of EventAnswer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventAnswer
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
