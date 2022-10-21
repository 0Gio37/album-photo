<?php

namespace App\Repository;

use App\Entity\Photo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Photo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Photo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Photo[]    findAll()
 * @method Photo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Photo::class);
    }

    public function findSinglePhotoOlder(int $id,  \DateTimeInterface $dateTime)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.album = :val1')
            ->andWhere('p.createAt > :val2')
            ->setParameter('val1', $id)
            ->setParameter('val2', $dateTime)
            ->orderBy('p.createAt', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    public function findSinglePhotoYounger(int $id,  \DateTimeInterface $dateTime)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.album = :val1')
            ->andWhere('p.createAt < :val2')
            ->setParameter('val1', $id)
            ->setParameter('val2', $dateTime)
            ->orderBy('p.createAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return Services[] Returns an array of Services objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Services
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */



}
