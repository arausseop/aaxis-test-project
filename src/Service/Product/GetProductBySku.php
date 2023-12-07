<?php

namespace App\Service\Product;

use App\Entity\Product;
use App\Model\Product\Exception\ProductNotFound;
use App\Repository\ProductRepository;


class GetProductBySku
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(string $productSku): Product
    {
        $product = $this->productRepository->findOneBy(['sku' => $productSku]);

        if (!$product) {
            ProductNotFound::throwException();
        }
        return $product;
    }
}
