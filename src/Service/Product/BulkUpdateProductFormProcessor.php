<?php

namespace App\Service\Product;

use App\Form\Product\DTO\ProductDto;
use App\Form\Product\Type\ProductFormType;
use App\Model\Exception\InvalidArgumentException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class BulkUpdateProductFormProcessor
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
        $productsToUpdate = json_decode($request->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        //TODO: THROW BAD REQUEST ERROR 
        if (empty($productsToUpdate)) {
            dd('return bad request error');
        }

        $arrayValidProducts = [];
        $arrayProductsErrors = [];

        foreach ($productsToUpdate as $key => $productToUpdate) {
            try {
                $validProduct = $this->validateProduct($productToUpdate);
                array_push($arrayValidProducts, $validProduct);
            } catch (\Exception $ex) {

                array_push($arrayProductsErrors, ['product' => $productToUpdate, 'error' => $ex->getMessage()]);
            }
        }

        if (!empty($arrayProductsErrors)) {
            return [null, $arrayProductsErrors];
        }

        $arrayUpdatedProducts = [];
        foreach ($arrayValidProducts as $validProduct) {

            $product = ($this->getProductBySku)($validProduct['sku']);
            $productDto = ProductDto::createFromProduct($product);

            $form = $this->formFactory->create(ProductFormType::class, $productDto);
            $form->submit($validProduct);

            if (!$form->isSubmitted()) {
                return [null, 'Form is not submitted'];
            }

            if (!$form->isValid()) {
                return [null, $form];
            }

            $paramsToUpdate = \array_keys($validProduct);
            foreach ($paramsToUpdate as $param) {
                $product->{\sprintf('%s%s', self::SETTER_PREFIX, \ucfirst($param))}($productDto->{$param});
            }

            $this->productManager->save($product);
            $this->productManager->reload($product);
            array_push($arrayUpdatedProducts, $product);
        }

        return [$arrayUpdatedProducts, null];
    }

    private function validateProduct(array $productToupdate): array
    {
        if (!isset($productToupdate['sku'])) {
            throw InvalidArgumentException::createFromNotExistArgument('sku');
        }
        return $productToupdate;
    }
}
