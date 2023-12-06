<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Product\CreateProductFormProcessor;
use App\Service\Product\DeleteProduct;
use App\Service\Product\GetProduct;
use App\Service\Product\GetProductBySku;
use App\Service\Product\ProductManager;
use App\Service\Product\UpdateProductFormProcessor;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(path: "/api/products", name: "api_products-")]
class ProductController extends AbstractFOSRestController
{
    #[Rest\Get('', name: 'list')]
    public function getAction(
        ProductManager $productManager,
        SerializerInterface $serializer,
        Request $request,
    ): Response {

        $products =  $productManager->getRepository()->findAll();
        $data = $serializer->serialize($products, 'json', ['groups' => ['products-list']]);
        return JsonResponse::fromJsonString($data, Response::HTTP_OK);
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
    ): Response {
        try {

            [$product, $error] = ($createProductFormProcessor)($request);
            $statusCode = $product ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
            $data = $product ?? $error;

            if ($statusCode !== 201) {
                return JsonResponse::fromJsonString($data, $statusCode);
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
    ): Response {

        try {

            [$product, $error] = ($updateProductFormProcessor)($request, $sku);
            $statusCode = $product ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
            $data = $product ?? $error;

            if ($statusCode !== 201) {
                return JsonResponse::fromJsonString($data, $statusCode);
            } else {
                $data = $serializer->serialize($product, 'json', ['groups' => ['products-create']]) ?? $error;

                return JsonResponse::fromJsonString($data, $statusCode);
            }
        } catch (\Throwable $exception) {
            return JsonResponse::fromJsonString($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Rest\Patch('/{id}', name: 'patch')]
    public function patchAction(): Response
    {
        return new JsonResponse('porduct partial edit', Response::HTTP_OK);
    }

    #[Rest\Put('/bulk/update', name: 'bulk-update')]
    public function bulkUpdateAction(): Response
    {
        return new JsonResponse('array porduct update', Response::HTTP_OK);
    }

    #[Rest\Delete('/{id}', name: 'delete')]
    public function deleteAction(int $id, DeleteProduct $deleteProduct): Response
    {
        try {
            ($deleteProduct)($id);
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (Exception $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Rest\Delete('/{id}/remove', name: 'remove')]
    public function removeAction(int $id, DeleteProduct $deleteProduct): Response
    {
        try {
            ($deleteProduct)($id, true);
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (Exception $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
