<?php

namespace App\Repository;

use App\Entity\User;
use App\Search\SearchEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;


/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{


    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, User::class);
        $this->paginator = $paginator;
    }

    /**
     * @param SearchEntity $search
     * @return PaginationInterface
     */
    public function findSearch(SearchEntity $search, Request $request): PaginationInterface
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('p')
            ->Where('p.username LIKE :motCle')
            ->setParameter('motCle', "%{$search->motCle}%");
        $results = $query->getQuery();

        return $this->paginator->paginate(
            $results,
            $request->query->getInt('page', 1), /*page number*/
            15
        );

    }

    public function findUserById($id)
    {
        $query = $this
            ->createQueryBuilder('u')
            ->select('u')
            ->andWhere('u.id IN (:userId)')
            ->setParameter('userId', $id);
        $result = $query->getQuery();
        return $result->getResult()[0];
    }


    public function findRandom()
    {
        $max = $this
            ->createQueryBuilder('u')
            ->select('MAX(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
           $min = $this
               ->createQueryBuilder('u')
               ->select('MIN(u.id)')
               ->getQuery()
               ->getSingleScalarResult();
        $query=$this->createQueryBuilder('u')
            ->select('u')
            ->where('u.id >= :rand')
            ->setParameter('rand',rand($min,$max))
            ->setMaxResults(5);
        return $query->getQuery()->getResult();
    }
}

