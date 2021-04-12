<?php

namespace App\Repository;

use App\Entity\FormationFollowingMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormationFollowingMethod|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormationFollowingMethod|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormationFollowingMethod[]    findAll()
 * @method FormationFollowingMethod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationFollowingMethodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormationFollowingMethod::class);
    }

    // /**
    //  * @return FormationFollowingMethod[] Returns an array of FormationFollowingMethod objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FormationFollowingMethod
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
