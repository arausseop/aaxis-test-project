<?php

namespace App\Service\Product;


class DeleteProduct
{
    private GetProduct $getProduct;
    private ProductManager $productManager;

    public function __construct(GetProduct $getProduct, ProductManager $productManager)
    {
        $this->getProduct = $getProduct;
        $this->productManager = $productManager;
    }

    public function __invoke(string $uuid)
    {
        $product = ($this->getProduct)($uuid);
        $this->productManager->delete($product);
    }
}
