<?php

namespace App\Repository;

use App\Entity\UECourse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UECourse|null find($id, $lockMode = null, $lockVersion = null)
 * @method UECourse|null findOneBy(array $criteria, array $orderBy = null)
 * @method UECourse[]    findAll()
 * @method UECourse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UECourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UECourse::class);
    }

    // /**
    //  * @return UECourse[] Returns an array of UECourse objects
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
    public function findOneBySomeField($value): ?UECourse
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
