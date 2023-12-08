<?php

declare(strict_types=1);

namespace App\Controller\API\Product;

use App\Model\Exception\DatabaseException;
use App\Model\Product\API\Filter\ProductFilter;
use App\Service\Product\BulkCreateProductFormProcessor;
use App\Service\Product\BulkUpdateProductFormProcessor;
use App\Service\Product\CreateProductFormProcessor;
use App\Service\Product\DeleteProduct;
use App\Service\Product\GetProduct;
use App\Service\Product\GetProductBySku;
use App\Service\Product\PatchProductFormProcessor;
use App\Service\Product\ProductManager;
use App\Service\Product\UpdateProductFormProcessor;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(path: "/api/products", name: "api_products-")]
class ProductController extends AbstractFOSRestController
{
    #[Rest\Get('', name: 'list')]
    #[QueryParam(name: 'page', requirements: '\d+', strict: false, nullable: true, allowBlank: true, description: 'page number')]
    #[QueryParam(name: 'limit', requirements: '\d+', strict: false, nullable: true, allowBlank: true, description: 'items Per Page')]
    #[QueryParam(name: 'sort', strict: false, nullable: true, allowBlank: true, description: 'Field to sort')]
    #[QueryParam(name: 'order', strict: false, nullable: true, allowBlank: true, description: 'Order method ')]
    #[QueryParam(name: 'searchText', strict: false, nullable: true, allowBlank: true, description: 'Text to search')]
    #[QueryParam(name: 'sku', strict: false, nullable: true, allowBlank: true, description: 'search by sku')]
    public function getAction(
        ?int $page,
        ?int $limit,
        ?string $sort,
        ?string $order,
        ?string $searchText,
        ?string $sku,
        ProductManager $productManager,
        SerializerInterface $serializer,
        Request $request,
    ) {

        $productFilter = new ProductFilter(
            $page,
            $limit,
            $sort,
            $order,
            $searchText,
            $sku
        );

        $productsResponse =  $productManager->getRepository()->findByCriteria($productFilter);
        $data = json_decode($serializer->serialize($productsResponse->getItems(), 'json', ['groups' => ['products-list']]), true, 512, JSON_THROW_ON_ERROR);

        return new JsonResponse(['items' => $data, 'meta' => $productsResponse->getMeta()], Response::HTTP_OK);
    }

    #[Rest\Get('/{sku}')]
    public function getSingleAction(
        string $sku,
        GetProductBySku $getProductBySku,
        SerializerInterface $serializer
    ) {
        try {
            $product = ($getProductBySku)($sku);
        } catch (Exception $exception) {
            return View::create($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        $data = $serializer->serialize($product, 'json', ['groups' => ['products-show']]);
        return JsonResponse::fromJsonString($data);
    }

    #[Rest\Post('', name: 'create')]
    public function createAction(
        CreateProductFormProcessor $createProductFormProcessor,
        Request $request,
        SerializerInterface $serializer,
    ) {
        try {

            [$product, $error] = ($createProductFormProcessor)($request);
            $statusCode = $product ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
            $data = $product ?? $error;

            if ($statusCode !== 201) {
                return View::create($data, $statusCode);
            } else {
                $data = $serializer->serialize($product, 'json', ['groups' => ['products-create']]) ?? $error;

                return JsonResponse::fromJsonString($data, $statusCode);
            }
        } catch (\Throwable $exception) {

            return JsonResponse::fromJsonString($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Rest\Put('/{sku}', name: 'update')]
    public function updateAction(
        string $sku,
        UpdateProductFormProcessor $updateProductFormProcessor,
        Request $request,
        SerializerInterface $serializer,
    ) {

        try {

            [$product, $error] = ($updateProductFormProcessor)($request, $sku);
            $statusCode = $product ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
            $data = $product ?? $error;

            if ($statusCode !== 201) {
                return View::create($data, $statusCode);
            } else {
                $data = $serializer->serialize($product, 'json', ['groups' => ['products-update']]) ?? $error;

                return JsonResponse::fromJsonString($data, $statusCode);
            }
        } catch (\Throwable $exception) {
            return JsonResponse::fromJsonString($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Rest\Patch('/{sku}', name: 'patch')]
    public function patchAction(
        string $sku,
        PatchProductFormProcessor $patchProductFormProcessor,
        Request $request,
        SerializerInterface $serializer,
    ) {

        try {

            [$product, $error] = ($patchProductFormProcessor)($request, $sku);
            $statusCode = $product ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
            $data = $product ?? $error;

            if ($statusCode !== 201) {
                return View::create($data, $statusCode);
            } else {
                $data = $serializer->serialize($product, 'json', ['groups' => ['products-update']]) ?? $error;

                return JsonResponse::fromJsonString($data, $statusCode);
            }
        } catch (\Throwable $exception) {
            if ($exception instanceof DatabaseException) {

                return View::create(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
                // return View::create(['error' => 'Oops!!!'], Response::HTTP_BAD_REQUEST);
            }
            return JsonResponse::fromJsonString($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Rest\Patch('/bulk/update', name: 'bulk-update')]
    public function bulkUpdateAction(
        BulkUpdateProductFormProcessor $bulkUpdateProductFormProcessor,
        Request $request,
        SerializerInterface $serializer,
    ) {

        [$product, $error] = ($bulkUpdateProductFormProcessor)($request);
        $statusCode = $product ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $product ?? $error;

        if ($statusCode !== 201) {
            return View::create($data, $statusCode);
        } else {
            $data = $serializer->serialize($product, 'json', ['groups' => ['products-update']]) ?? $error;

            return JsonResponse::fromJsonString($data, $statusCode);
        }

        return new JsonResponse('array porduct update', Response::HTTP_OK);
    }

    #[Rest\Post('/bulk/create', name: 'bulk-create')]
    public function bulkCreateAction(
        BulkCreateProductFormProcessor $bulkCreateProductFormProcessor,
        Request $request,
        SerializerInterface $serializer,
    ) {
        try {
            [$product, $error] = ($bulkCreateProductFormProcessor)($request);
            $statusCode = $product ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
            $data = $product ?? $error;

            if ($statusCode !== 201) {
                return View::create($data, $statusCode);
            } else {
                $data = $serializer->serialize($product, 'json', ['groups' => ['bulk-products-create']]) ?? $error;

                return JsonResponse::fromJsonString($data, $statusCode);
            }
        } catch (\Exception $ex) {
            return View::create($ex->getMessage(), $ex->getCode());
        }

        return new JsonResponse('array porduct update', Response::HTTP_OK);
    }

    #[Rest\Delete('/{sku}', name: 'delete')]
    public function deleteAction(string $sku, DeleteProduct $deleteProduct): Response
    {
        try {
            ($deleteProduct)($sku);
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (Exception $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Rest\Delete('/{sku}/remove', name: 'remove')]
    public function removeAction(string $sku, DeleteProduct $deleteProduct): Response
    {
        try {
            ($deleteProduct)($sku, true);
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (Exception $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
