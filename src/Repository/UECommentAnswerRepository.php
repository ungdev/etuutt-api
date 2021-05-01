<?php

namespace App\Repository;

use App\Entity\UECommentAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UECommentAnswer find($id, $lockMode = null, $lockVersion = null)
 * @method null|UECommentAnswer findOneBy(array $criteria, array $orderBy = null)
 * @method UECommentAnswer[]    findAll()
 * @method UECommentAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UECommentAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UECommentAnswer::class);
    }

    // /**
    //  * @return UECommentAnswer[] Returns an array of UECommentAnswer objects
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
    public function findOneBySomeField($value): ?UECommentAnswer
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
