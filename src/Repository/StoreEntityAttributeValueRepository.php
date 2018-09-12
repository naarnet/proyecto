<?php

namespace App\Repository;

use App\Entity\StoreEntityAttributeValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method StoreEntityAttributeValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method StoreEntityAttributeValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method StoreEntityAttributeValue[]    findAll()
 * @method StoreEntityAttributeValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoreEntityAttributeValueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StoreEntityAttributeValue::class);
    }

//    /**
//     * @return StoreEntityAttributeValue[] Returns an array of StoreEntityAttributeValue objects
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
    public function findOneBySomeField($value): ?StoreEntityAttributeValue
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
