<?php

namespace App\Service\Product;


class DeleteProduct
{

    public function __construct(
        private GetProductBySku $getProductBySku,
        private ProductManager $productManager
    ) {
    }

    public function __invoke(string $productIdentifier, bool $physically = false)
    {
        $product = ($this->getProductBySku)($productIdentifier);
        !$physically
            ? $this->productManager->delete($product)
            : $this->productManager->remove($product);
    }
}
