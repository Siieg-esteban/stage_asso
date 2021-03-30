<?php

namespace App\Repository;

use App\Entity\RequeteContributeur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RequeteContributeur|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequeteContributeur|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequeteContributeur[]    findAll()
 * @method RequeteContributeur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequeteContributeurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequeteContributeur::class);
    }

    // /**
    //  * @return RequeteContributeur[] Returns an array of RequeteContributeur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RequeteContributeur
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
