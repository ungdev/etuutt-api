<?php

namespace App\Repository;

use App\Entity\UECommentUpvote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UECommentUpvote find($id, $lockMode = null, $lockVersion = null)
 * @method null|UECommentUpvote findOneBy(array $criteria, array $orderBy = null)
 * @method UECommentUpvote[]    findAll()
 * @method UECommentUpvote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UECommentUpvoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UECommentUpvote::class);
    }

    // /**
    //  * @return UECommentUpvote[] Returns an array of UECommentUpvote objects
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
    public function findOneBySomeField($value): ?UECommentUpvote
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
