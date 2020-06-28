<?php

namespace App\Repository;

use App\Entity\Post;
use App\Search\SearchEntity;
use App\Search\SearchHome;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

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

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Post::class);
        $this->paginator = $paginator;
    }

    /**
     * @param SearchHome $search
     * @return PaginationInterface
     */
    public function findSearch(SearchHome $search): PaginationInterface
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p')
            ->join('p.Categories', 'c');
        if (!empty($search->getMin())) {
            $query = $query
                ->andWhere('p.nblikes >= :min')
                ->setParameter('min', $search->getMin());
        }
        if (!empty($search->getMax())) {
            $query = $query
                ->andWhere('p.nblikes <= :max')
                ->setParameter('max', $search->getMax());
        }
        if (!empty($search->getCategories())) {
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->getCategories());
        }

        $results = $query->getQuery();
        return $this->paginator->paginate(
            $results,
            $search->page,
            10
        );
    }

    public function findCategory(SearchHome $search, Request $request)
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p')
            ->join('p.Categories', 'c');

        if (!empty($search->getCategories())) {
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->getCategories());
        }

        $results = $query->getQuery();
        return $this->paginator->paginate(
            $results,
            $request->query->getInt('page', 1), /*page number*/
            10
        );

    }

    /**
     * @param SearchEntity $search
     * @param Request $request
     * @return PaginationInterface
     */
    public function findByTagName(SearchEntity $search, Request $request): PaginationInterface
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('t', 'p')
            ->join('p.tags', 't');
        $query = $query
            ->andWhere('t.value IN (:tag)')
            ->setParameter('tag', $search->motCle);


        $results = $query->getQuery();
        return $this->paginator->paginate(
            $results,
            $request->query->getInt('page', 1), /*page number*/
            10
        );
    }

    /**
     * @param SearchEntity $search
     * @param Request $request
     * @return PaginationInterface
     */
    public function findByPostTitle(SearchEntity $search, Request $request): PaginationInterface
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('p')
            ->Where('p.title LIKE :motCle')
            ->setParameter('motCle', "%{$search->motCle}%");
        $results = $query->getQuery();
        return $this->paginator->paginate(
            $results,
            $request->query->getInt('page', 1), /*page number*/
            10
        );
    }

    /**
     * @param SearchEntity $data
     * @param Request $request
     * @return PaginationInterface
     */
    public function findDescriptipn(SearchEntity $data, Request $request/*,SearchHome $search*/): PaginationInterface
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('p')
/*            ->join('p.Categories', 'c')*/
            ->Where('p.description LIKE :motCle')
            ->setParameter('motCle', "%{$data->motCle}%");
        /*if (!empty($search->getMin())) {
            $query = $query
                ->andWhere('p.nblikes >= :min')
                ->setParameter('min', $search->getMin());
        }
        if (!empty($search->getMax())) {
            $query = $query
                ->andWhere('p.nblikes <= :max')
                ->setParameter('max', $search->getMax());
        }
        if (!empty($search->getCategories())) {
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->getCategories());
        }*/


        $results = $query->getQuery();
        return $this->paginator->paginate(
            $results,
            $request->query->getInt('page', 1), /*page number*/
            10
        );


    }

    /**
     * @param UserInterface|null $user
     * @param Request $request
     * @param SearchHome $search
     * @return PaginationInterface
     */

    public function findPost(?UserInterface $user, Request $request, SearchHome $search): PaginationInterface
    {

        $query = $this
            ->createQueryBuilder('p')
            ->select('u', 'p')
            ->join('p.user', 'u')
            ->andWhere('u.id IN (:motCle)')
            ->setParameter('motCle', $user->getSubscribedTo());
        if (!empty($search->getMin())) {
            $query = $query
                ->andWhere('p.nblikes >= :min')
                ->setParameter('min', $search->getMin());
        }
        if (!empty($search->getMax())) {
            $query = $query
                ->andWhere('p.nblikes <= :max')
                ->setParameter('max', $search->getMax());
        }
        if (!empty($search->getCategories())) {
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->getCategories());
        }

        $results = $query->getQuery();
        return $this->paginator->paginate(
            $results,
            $request->query->getInt('page', 1), /*page number*/
            10
        );


    }

    public function findPostById($id)
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('p')
            ->andWhere('p.id IN (:postId)')
            ->setParameter('postId', $id);
        $result = $query->getQuery();
        return $result->getResult()[0];
    }
//
//
//
//        $results = $query->getQuery();
//        return $this->paginator->paginate(
//            $results,
//            $request->query->getInt('page', 1), /*page number*/
//            10
//        );
//    }

    public function findPostByTag(string $tag, Request $request, SearchHome $search)
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('t', 'p', 'c')
            ->join('p.tags', 't')
            ->join('p.Categories', 'c')
            ->andWhere('t.value IN (:tag)')
            ->setParameter('tag', $tag);
        if (!empty($search->getMin())) {
            $query = $query
                ->andWhere('p.nblikes >= :min')
                ->setParameter('min', $search->getMin());
        }
        if (!empty($search->getMax())) {
            $query = $query
                ->andWhere('p.nblikes <= :max')
                ->setParameter('max', $search->getMax());
        }
        if (!empty($search->getCategories())) {
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->getCategories());
        }

        $results = $query->getQuery();
        return $this->paginator->paginate(
            $results,
            $request->query->getInt('page', 1), /*page number*/
            10
        );

    }

    public function findTagCategory($tagName,SearchHome $search, Request $request)
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p')
            ->join('p.tags', 't')
            ->join('p.Categories', 'c')
            ->andWhere('t.value IN (:tag)')
            ->setParameter('tag', $tagName);

        if (!empty($search->getCategories())) {
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->getCategories());
        }

        $results = $query->getQuery();
        return $this->paginator->paginate(
            $results,
            $request->query->getInt('page', 1), /*page number*/
            10
        );
    }
}