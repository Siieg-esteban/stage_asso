<?php

namespace App\Repository;

use App\Entity\Imagefichierrequete;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Imagefichierrequete|null find($id, $lockMode = null, $lockVersion = null)
 * @method Imagefichierrequete|null findOneBy(array $criteria, array $orderBy = null)
 * @method Imagefichierrequete[]    findAll()
 * @method Imagefichierrequete[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagefichierrequeteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Imagefichierrequete::class);
    }

    // /**
    //  * @return Imagefichierrequete[] Returns an array of Imagefichierrequete objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Imagefichierrequete
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
