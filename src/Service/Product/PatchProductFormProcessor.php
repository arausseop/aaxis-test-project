<?php

namespace App\Service\Product;

use App\Form\Product\DTO\ProductDto;
use App\Form\Product\Type\ProductFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class PatchProductFormProcessor
{
    private const SETTER_PREFIX = 'set';

    public function __construct(
        private GetProductBySku $getProductBySku,
        private ProductManager $productManager,
        private FormFactoryInterface $formFactory
    ) {
    }

    public function __invoke(Request $request, string $productIdentifier): array
    {
        $product = ($this->getProductBySku)($productIdentifier);

        $productDto = ProductDto::createFromProduct($product);

        $content = json_decode($request->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        $paramsToUpdate = \array_keys($content);

        $form = $this->formFactory->create(ProductFormType::class, $productDto, ['validation_groups' => ["BulkUpdate"]]);
        $form->submit($content);

        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }

        if (!$form->isValid()) {
            return [null, $form];
        }

        foreach ($paramsToUpdate as $param) {
            $product->{\sprintf('%s%s', self::SETTER_PREFIX, \ucfirst($param))}($productDto->{$param});
        }

        $this->productManager->save($product);
        $this->productManager->reload($product);
        return [$product, null];
    }
}
