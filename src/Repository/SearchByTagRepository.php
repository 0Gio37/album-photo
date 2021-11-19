<?php

namespace App\Repository;

use App\Entity\SearchByTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SearchByTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method SearchByTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method SearchByTag[]    findAll()
 * @method SearchByTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SearchByTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchByTag::class);
    }

    // /**
    //  * @return SearchByTag[] Returns an array of SearchByTag objects
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
    public function findOneBySomeField($value): ?SearchByTag
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
