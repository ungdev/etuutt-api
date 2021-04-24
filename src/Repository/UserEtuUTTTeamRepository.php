<?php

namespace App\Repository;

use App\Entity\UserEtuUTTTeam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UserEtuUTTTeam find($id, $lockMode = null, $lockVersion = null)
 * @method null|UserEtuUTTTeam findOneBy(array $criteria, array $orderBy = null)
 * @method UserEtuUTTTeam[]    findAll()
 * @method UserEtuUTTTeam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserEtuUTTTeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserEtuUTTTeam::class);
    }

    // /**
    //  * @return UserEtuUTTTeam[] Returns an array of UserEtuUTTTeam objects
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
    public function findOneBySomeField($value): ?UserEtuUTTTeam
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
