<?php

namespace App\Repository;

use App\Entity\UTTFormationFollowingMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UTTFormationFollowingMethod find($id, $lockMode = null, $lockVersion = null)
 * @method null|UTTFormationFollowingMethod findOneBy(array $criteria, array $orderBy = null)
 * @method UTTFormationFollowingMethod[]    findAll()
 * @method UTTFormationFollowingMethod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UTTFormationFollowingMethodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UTTFormationFollowingMethod::class);
    }

    // /**
    //  * @return UTTFormationFollowingMethod[] Returns an array of UTTFormationFollowingMethod objects
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
    public function findOneBySomeField($value): ?UTTFormationFollowingMethod
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
