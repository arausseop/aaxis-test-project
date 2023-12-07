<?php

namespace App\Service\Product;

use App\Form\Product\DTO\ProductDto;
use App\Form\Product\Type\ProductFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class CreateProductFormProcessor
{

    public function __construct(
        private ProductManager $productManager,
        private FormFactoryInterface $formFactory
    ) {
    }

    public function __invoke(Request $request): array
    {
        $productDto = ProductDto::createEmpty();

        $content = json_decode($request->getContent(), true, 512, \JSON_THROW_ON_ERROR);
        $form = $this->formFactory->create(ProductFormType::class, $productDto);
        $form->submit($content);

        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }

        if (!$form->isValid()) {
            return [null, $form];
        }

        $product = $this->productManager->create();

        $product->setSku($productDto->getSku());
        $product->setProductName($productDto->getProductName());
        $product->setDescription($productDto->getDescription());

        $this->productManager->save($product);
        $this->productManager->reload($product);
        return [$product, null];
    }
}
