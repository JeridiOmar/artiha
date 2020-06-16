<?php

namespace App\Repository;

use App\Entity\Post;
use App\Home\SearchEntity;
use App\Home\SearchHome;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator )
    {
        parent::__construct($registry, Post::class);
        $this->paginator = $paginator;
    }

    /**
     * @param SearchHome $search
     * @return PaginationInterface
     */
    public function findSearch(SearchHome $search):PaginationInterface
    {
        $query =$this
            ->createQueryBuilder('p')
            ->select('c','p')
            ->join('p.Categories','c');
            if(!empty($search->getMin())){
                $query=$query
                    ->andWhere('p.nblikes >= :min')
                    ->setParameter('min',$search->getMin());
            }
            if(!empty($search->getMax())){
                $query=$query
                    ->andWhere('p.nblikes <= :max')
                    ->setParameter('max',$search->getMax());
            }
            if (!empty($search->getCategories())){
                $query=$query
                    ->andWhere('c.id IN (:categories)')
                    ->setParameter('categories',$search->getCategories());
            }

        $results= $query->getQuery();
            return $this->paginator->paginate(
                $results,
                $search->page,
                10
            );
    }

    public function findCategory(SearchHome $search)
    {
        $query =$this
            ->createQueryBuilder('p')
            ->select('c','p')
            ->join('p.Categories','c');

        if (!empty($search->getCategories())){
            $query=$query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories',$search->getCategories());
        }

        $results= $query->getQuery();
        return $this->paginator->paginate(
            $results,
            $search->page,
            10
        );
    }

    public function findByTagName(SearchEntity $search)
    {
        $query =$this
            ->createQueryBuilder('p')
            ->select('t','p')
            ->join('p.tags','t');
         $query=$query
                ->andWhere('t.value IN (:tag)')
                ->setParameter('tag',$search->motCle);


        return $results= $query->getQuery()->getResult();

    }

    public function findByPostTitle(SearchEntity $search)
    {
        $query =$this
            ->createQueryBuilder('p')
            ->select('p')
            ->Where('p.title LIKE :motCle')
            ->setParameter('motCle',"%{$search->motCle}%");
        return $results= $query->getQuery()->getResult();

    }


    public function findDescriptipn(SearchEntity $search)
    {
        $query =$this
            ->createQueryBuilder('p')
            ->select('p')
            ->Where('p.description LIKE :motCle')
            ->setParameter('motCle',"%{$search->motCle}%");
        return $results= $query->getQuery()->getResult();
    }
}
