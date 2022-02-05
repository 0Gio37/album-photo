<?php

namespace App\Repository;

use App\Entity\LienCommentPhoto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LienCommentPhoto|null find($id, $lockMode = null, $lockVersion = null)
 * @method LienCommentPhoto|null findOneBy(array $criteria, array $orderBy = null)
 * @method LienCommentPhoto[]    findAll()
 * @method LienCommentPhoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LienCommentPhotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LienCommentPhoto::class);
    }

    // /**
    //  * @return LienCommentPhoto[] Returns an array of LienCommentPhoto objects
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
    public function findOneBySomeField($value): ?LienCommentPhoto
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
