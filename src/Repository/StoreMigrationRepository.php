<?php

namespace App\Repository;

use App\Entity\StoreMigration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Store|null find($id, $lockMode = null, $lockVersion = null)
 * @method Store|null findOneBy(array $criteria, array $orderBy = null)
 * @method Store[]    findAll()
 * @method Store[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoreMigrationRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StoreMigration::class);
    }

    /**
     * @return Store[] Returns an array of Store objects
     */
    public function getStoreQueryByUser($user)
    {
        return $this->createQueryBuilder('s')
                        ->andWhere('s.user = :user')
                        ->setParameter('user', $user)
                        ->orderBy('s.id', 'ASC')
        ;
    }

    /*
      public function findOneBySomeField($value): ?Store
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
