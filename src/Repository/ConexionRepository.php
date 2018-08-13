<?php

namespace App\Repository;

use App\Entity\Conexion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Conexion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conexion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conexion[]    findAll()
 * @method Conexion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConexionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Conexion::class);
    }

//    /**
//     * @return Conexion[] Returns an array of Conexion objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Conexion
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
