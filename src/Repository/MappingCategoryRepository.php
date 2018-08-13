<?php

namespace App\Repository;

use App\Entity\MappingCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MappingCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MappingCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MappingCategory[]    findAll()
 * @method MappingCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MappingCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MappingCategory::class);
    }

//    /**
//     * @return MappingCategory[] Returns an array of MappingCategory objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MappingCategory
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
