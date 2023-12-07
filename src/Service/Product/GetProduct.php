<?php

namespace App\Service\Product;

use App\Entity\Product;
use App\Model\Product\Exception\ProductNotFound;
use App\Repository\ProductRepository;


class GetProduct
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(int $id): Product
    {
        $product = $this->productRepository->findOneById($id);

        if (!$product) {
            ProductNotFound::throwException();
        }
        return $product;
    }
}
