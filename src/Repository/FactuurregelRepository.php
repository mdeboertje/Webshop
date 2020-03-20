<?php

namespace App\Repository;

use App\Entity\Factuurregel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Factuurregel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Factuurregel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Factuurregel[]    findAll()
 * @method Factuurregel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactuurregelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Factuurregel::class);
    }

    // /**
    //  * @return Factuurregel[] Returns an array of Factuurregel objects
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
    public function findOneBySomeField($value): ?Factuurregel
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
