<?php

namespace App\Repository;

use App\Entity\LienTagPhoto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LienTagPhoto|null find($id, $lockMode = null, $lockVersion = null)
 * @method LienTagPhoto|null findOneBy(array $criteria, array $orderBy = null)
 * @method LienTagPhoto[]    findAll()
 * @method LienTagPhoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LienTagPhotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LienTagPhoto::class);
    }

    // /**
    //  * @return LienTagPhoto[] Returns an array of LienTagPhoto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LienTagPhoto
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
