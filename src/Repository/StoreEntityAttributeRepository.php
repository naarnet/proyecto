<?php

namespace App\Repository;

use App\Entity\StoreEntityAttribute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method StoreEntityAttribute|null find($id, $lockMode = null, $lockVersion = null)
 * @method StoreEntityAttribute|null findOneBy(array $criteria, array $orderBy = null)
 * @method StoreEntityAttribute[]    findAll()
 * @method StoreEntityAttribute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoreEntityAttributeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StoreEntityAttribute::class);
    }

//    /**
//     * @return StoreEntityAttribute[] Returns an array of StoreEntityAttribute objects
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
    public function findOneBySomeField($value): ?StoreEntityAttribute
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
