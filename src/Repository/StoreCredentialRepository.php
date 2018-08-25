<?php

namespace App\Repository;

use App\Entity\StoreCredential;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method StoreCredential|null find($id, $lockMode = null, $lockVersion = null)
 * @method StoreCredential|null findOneBy(array $criteria, array $orderBy = null)
 * @method StoreCredential[]    findAll()
 * @method StoreCredential[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoreCredentialRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StoreCredential::class);
    }

//    /**
//     * @return StoreCredential[] Returns an array of StoreCredential objects
//     */
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
    public function findOneBySomeField($value): ?StoreCredential
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
