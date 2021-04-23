<?php

namespace App\Repository;

use App\Entity\GitHubIssue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|GitHubIssue find($id, $lockMode = null, $lockVersion = null)
 * @method null|GitHubIssue findOneBy(array $criteria, array $orderBy = null)
 * @method GitHubIssue[]    findAll()
 * @method GitHubIssue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GitHubIssueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GitHubIssue::class);
    }

    // /**
    //  * @return GitHubIssue[] Returns an array of GitHubIssue objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GitHubIssue
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
