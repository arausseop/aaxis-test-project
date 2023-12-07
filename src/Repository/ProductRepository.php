<?php

namespace App\Repository;

use App\Entity\Product;
use App\Model\Product\API\Filter\ProductFilter;
use App\Model\Product\API\Response\PaginatedResponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findByCriteria(ProductFilter $filter): PaginatedResponse
    {
        $page = $filter->page;
        $limit = $filter->limit;
        $sort = $filter->sort;
        $order = $filter->order;
        $searchText = $filter->searchText;
        $sku = $filter->sku;

        $qb = $this->createQueryBuilder('p');
        $qb->orderBy(\sprintf('p.%s', $sort), $order);

        if (null !== $searchText) {
            $qb
                ->andWhere('p.productName LIKE :searchText')
                ->setParameter(':searchText', $searchText . '%');
        }

        if (null !== $sku) {
            $qb
                ->andWhere('p.sku LIKE :sku')
                ->setParameter(':sku', $sku . '%');
        }

        $paginator = new Paginator($qb->getQuery());
        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        return PaginatedResponse::create(iterator_to_array($paginator->getIterator()), $paginator->count(), $page, $limit);
    }
}
