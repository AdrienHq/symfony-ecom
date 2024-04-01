<?php

namespace App\Repository;

use App\Data\category\SearchDataCategory;
use App\Data\recipe\SearchData;
use App\Entity\Recipes;
use App\Form\category\SearchFormCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Recipes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipes[]    findAll()
 * @method Recipes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipesRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry                     $registry,
        private readonly PaginatorInterface $paginator)
    {
        parent::__construct($registry, Recipes::class);
    }

    public function findRecipesForSpecificPage(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->createQueryBuilder('r')->leftJoin('r.category', 'c')->select('r', 'c'),
            $page,
            10,
            [
                'distinct' => false,
                'sortFieldAllowList' => ['r.id', 'r.name', 'r.createdAt', 'r.updatedAt']
            ]
        );
    }

    public function findSearch(SearchData $search): PaginationInterface
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('p', 'c', 'e')
            ->join('p.category', 'c')
            ->join('p.course', 'e');

        if ($search->q !== '') {
            $query = $query
                ->andWhere('p.name LIKE :q ')
                ->setParameter('q', "%{$search->q}%");
        }

        if (!empty($search->minDuration)) {
            $query = $query
                ->andWhere('p.duration >= :minDuration')
                ->setParameter('minDuration', $search->minDuration);
        }

        if (!empty($search->maxDuration)) {
            $query = $query
                ->andWhere('p.duration >= :maxDuration')
                ->setParameter('maxDuration', $search->maxDuration);
        }

        if (!empty($search->vegetarian)) {
            $query = $query
                ->andWhere('p.vegetarian = 1');
        }

        if (!empty($search->category)) {
            $query = $query
                ->andWhere('c.id IN (:category)')
                ->setParameter('category', $search->category);
        }

        if (!empty($search->course)) {
            $query = $query
                ->andWhere('e.id IN (:course)')
                ->setParameter('course', $search->course);
        }

        $query = $query->getQuery();

        return $this->paginator->paginate(
            $query,
            $search->page,
            9
        );
    }

    public function findSearchForCategory(SearchDataCategory $search): PaginationInterface
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('p', 'c', 'e')
            ->join('p.category', 'c')
            ->join('p.course', 'e');

        if ($search->q !== '') {
            $query = $query
                ->andWhere('p.name LIKE :q ')
                ->setParameter('q', "%{$search->q}%");
        }

        if (!empty($search->minDuration)) {
            $query = $query
                ->andWhere('p.duration >= :minDuration')
                ->setParameter('minDuration', $search->minDuration);
        }

        if (!empty($search->maxDuration)) {
            $query = $query
                ->andWhere('p.duration >= :maxDuration')
                ->setParameter('maxDuration', $search->maxDuration);
        }

        if (!empty($search->vegetarian)) {
            $query = $query
                ->andWhere('p.vegetarian = 1');
        }

        if (!empty($search->course)) {
            $query = $query
                ->andWhere('e.id IN (:course)')
                ->setParameter('course', $search->course);
        }

        $query = $query->getQuery();

        return $this->paginator->paginate(
            $query,
            $search->page,
            9
        );
    }

//    /**
//     * @return Recipes[] Returns an array of Recipes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Recipes
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
