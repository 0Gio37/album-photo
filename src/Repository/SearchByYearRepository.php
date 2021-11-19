<?php

namespace App\Repository;

use App\Entity\SearchByYear;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SearchByYear|null find($id, $lockMode = null, $lockVersion = null)
 * @method SearchByYear|null findOneBy(array $criteria, array $orderBy = null)
 * @method SearchByYear[]    findAll()
 * @method SearchByYear[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SearchByYearRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchByYear::class);
    }

    // /**
    //  * @return SearchByYear[] Returns an array of SearchByYear objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SearchByYear
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
