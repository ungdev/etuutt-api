<?php

namespace App\Repository;

use App\Entity\UEStarVote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UEStarVote find($id, $lockMode = null, $lockVersion = null)
 * @method null|UEStarVote findOneBy(array $criteria, array $orderBy = null)
 * @method UEStarVote[]    findAll()
 * @method UEStarVote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UEStarVoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UEStarVote::class);
    }

    // /**
    //  * @return UEStarVote[] Returns an array of UEStarVote objects
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
    public function findOneBySomeField($value): ?UEStarVote
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
