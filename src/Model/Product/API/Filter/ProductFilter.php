<?php

declare(strict_types=1);

namespace App\Model\Product\API\Filter;

final class ProductFilter
{
    private const PAGE = 1;
    private const LIMIT = 10;

    private const ALLOWED_SORT_PARAMS = ['productName', 'sku'];
    private const ALLOWED_ORDER_PARAMS = ['asc', 'desc'];

    private const DAFAULT_SORT = 'productName';
    private const DAFAULT_ORDER = 'asc';

    public readonly ?int $page;
    public readonly ?int $limit;

    public readonly string $sort;
    public readonly string $order;

    public readonly ?string $searchText;
    public readonly ?string $sku;

    public function __construct(
        ?int $page,
        ?int $limit,
        ?string $sort,
        ?string $order,
        ?string $searchText,
        ?string $sku,
    ) {
        if (null !== $page) {
            $this->page = $page;
        } else {
            $this->page = self::PAGE;
        }

        if (null !== $limit) {
            $this->limit = $limit;
        } else {
            $this->limit = self::LIMIT;
        }

        if (null == $order) {
            $this->order = self::DAFAULT_ORDER;
        } else {
            $this->order = $order;
        }

        if (null == $sort) {
            $this->sort = self::DAFAULT_SORT;
        } else {
            $this->sort = $sort;
        }

        if (null !== $searchText) {
            $this->searchText = $searchText;
        } else {
            $this->searchText = null;
        }

        if (null !== $sku) {
            $this->sku = $sku;
        } else {
            $this->sku = null;
        }


        $this->validateSort($this->sort);
        $this->validateOrder($this->order);
    }

    private function validateSort(string $sort): void
    {
        if (!\in_array($sort, self::ALLOWED_SORT_PARAMS, true)) {
            throw new \InvalidArgumentException(\sprintf('Invalid sort param [%s]', $sort));
        }
    }

    private function validateOrder(string $order): void
    {
        if (!\in_array($order, self::ALLOWED_ORDER_PARAMS, true)) {
            throw new \InvalidArgumentException(\sprintf('Invalid order param [%s]', $order));
        }
    }
}
