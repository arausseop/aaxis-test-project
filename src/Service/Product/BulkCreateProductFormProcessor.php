<?php

namespace App\Service\Product;

use App\Form\Product\DTO\ProductDto;
use App\Form\Product\Type\ProductFormType;
use App\Model\Exception\InvalidArgumentException;
use Exception;
use Symfony\Component\Form\FormFactoryInterface;
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

        //TODO: THROW BAD REQUEST ERROR 
        if (empty($productsToCreate)) {
            dd('return bad request error');
        }

        $arrayProductsDto = [];
        $arrayProductsErrors = [];

        foreach ($productsToCreate as $key => $productToCreate) {
            try {
                $validProduct = $this->validateProduct($productToCreate);
                array_push($arrayProductsDto, $validProduct);
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

    private function validateProduct(array $productToCreate): ProductDto
    {

        $productDto = ProductDto::createEmpty();

        $form = $this->formFactory->create(ProductFormType::class, $productDto);
        $form->submit($productToCreate);

        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }

        //TODO: THROW Validate Error 
        if (!$form->isValid()) {
            dd('invalid form', $form->getErrors());
            // return [null, $form];
        }

        return $productDto;
    }
}
