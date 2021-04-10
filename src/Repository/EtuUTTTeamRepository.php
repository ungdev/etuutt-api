<?php

namespace App\Repository;

use App\Entity\EtuUTTTeam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EtuUTTTeam|null find($id, $lockMode = null, $lockVersion = null)
 * @method EtuUTTTeam|null findOneBy(array $criteria, array $orderBy = null)
 * @method EtuUTTTeam[]    findAll()
 * @method EtuUTTTeam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtuUTTTeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EtuUTTTeam::class);
    }

    // /**
    //  * @return EtuUTTTeam[] Returns an array of EtuUTTTeam objects
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
    public function findOneBySomeField($value): ?EtuUTTTeam
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
