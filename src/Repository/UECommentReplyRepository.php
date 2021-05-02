<?php

namespace App\Repository;

use App\Entity\UECommentReply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UECommentReply find($id, $lockMode = null, $lockVersion = null)
 * @method null|UECommentReply findOneBy(array $criteria, array $orderBy = null)
 * @method UECommentReply[]    findAll()
 * @method UECommentReply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UECommentReplyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UECommentReply::class);
    }

    // /**
    //  * @return UECommentReply[] Returns an array of UECommentReply objects
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
    public function findOneBySomeField($value): ?UECommentReply
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
