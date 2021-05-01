<?php

namespace App\Repository;

use App\Entity\UEComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UEComment find($id, $lockMode = null, $lockVersion = null)
 * @method null|UEComment findOneBy(array $criteria, array $orderBy = null)
 * @method UEComment[]    findAll()
 * @method UEComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UECommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UEComment::class);
    }

    // /**
    //  * @return UEComment[] Returns an array of UEComment objects
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
    public function findOneBySomeField($value): ?UEComment
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
