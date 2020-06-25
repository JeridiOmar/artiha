<?php

namespace App\Repository;

use App\Entity\ChangePwd;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ChangePwd|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChangePwd|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChangePwd[]    findAll()
 * @method ChangePwd[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChangePwdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChangePwd::class);
    }

    // /**
    //  * @return ChangePwd[] Returns an array of ChangePwd objects
    //  */
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
    public function findOneBySomeField($value): ?ChangePwd
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
