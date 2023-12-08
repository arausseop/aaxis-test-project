<?php

namespace App\Service\Product;

use App\Form\Product\DTO\ProductDto;
use App\Form\Product\Type\ProductFormType;
use App\Model\Exception\CustomFormException;
use App\Model\Exception\InvalidArgumentException;
use Exception;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

class BulkCreateProductFormProcessor
{

    private const SETTER_PREFIX = 'set';

    public function __construct(
        private GetProductBySku $getProductBySku,
        private ProductManager $productManager,
        private FormFactoryInterface $formFactory
    ) {
    }

    public function __invoke(Request $request): array
    {
        $productsToCreate = json_decode($request->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        if (empty($productsToCreate)) {
            throw InvalidArgumentException::createFromMessage('Empty value Request');
            // throw new BadRequestException('Empty value Request', 400);
        }

        $arrayProductsDto = [];
        $arrayProductsErrors = [];

        foreach ($productsToCreate as $key => $productToCreate) {
            try {
                $validProduct = $this->validateProduct($productToCreate);
                if ($validProduct['error']) {

                    array_push($arrayProductsErrors, ['product' => $productToCreate, 'error' => $validProduct['error']]);
                } else {

                    array_push($arrayProductsDto, $validProduct['productDto']);
                }
                // array_push($arrayProductsDto, $validProduct['productDto']);
            } catch (\Exception $ex) {

                array_push($arrayProductsErrors, ['product' => $productToCreate, 'error' => $ex->getMessage()]);
            }
        }

        if (!empty($arrayProductsErrors)) {
            return [null, $arrayProductsErrors];
        }

        $arrayProductsCreated = [];
        foreach ($arrayProductsDto as $productDto) {

            $product = $this->productManager->create();
            $product->setSku($productDto->getSku());
            $product->setProductName($productDto->getProductName());
            $product->setDescription($productDto->getDescription());

            $this->productManager->save($product);
            array_push($arrayProductsCreated, $product);
        }

        return [$arrayProductsCreated, null];
    }

    private function validateProduct(array $productToCreate)
    {

        $productDto = ProductDto::createEmpty();

        $form = $this->formFactory->create(ProductFormType::class, $productDto, ['validation_groups' => ["BulkCreate"]]);
        $form->submit($productToCreate);

        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }

        if (!$form->isValid()) {
            return ['productDto' => null, 'error' => $form];
        }

        return ['productDto' => $productDto, "error" => null];
    }
}
