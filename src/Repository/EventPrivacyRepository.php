<?php

namespace App\Repository;

use App\Entity\EventPrivacy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|EventPrivacy find($id, $lockMode = null, $lockVersion = null)
 * @method null|EventPrivacy findOneBy(array $criteria, array $orderBy = null)
 * @method EventPrivacy[]    findAll()
 * @method EventPrivacy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventPrivacyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventPrivacy::class);
    }

    // /**
    //  * @return EventPrivacy[] Returns an array of EventPrivacy objects
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
    public function findOneBySomeField($value): ?EventPrivacy
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
