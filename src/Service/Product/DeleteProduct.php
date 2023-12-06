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

    public function __invoke(int $id, bool $physically = false)
    {
        $product = ($this->getProduct)($id);
        !$physically
            ? $this->productManager->delete($product)
            : $this->productManager->remove($product);
    }
}
