<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Product\DeleteProduct;
use App\Service\Product\GetProduct;
use App\Service\Product\ProductManager;
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

    #[Rest\Get('/{id}')]
    public function getSingleAction(
        int $id,
        GetProduct $getProduct,
        SerializerInterface $serializer
    ) {
        try {
            $product = ($getProduct)($id);
        } catch (Exception $exception) {
            return View::create($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        $data = $serializer->serialize($product, 'json', ['groups' => ['products-show']]);
        return JsonResponse::fromJsonString($data);
    }

    #[Rest\Post('', name: 'create')]
    public function createAction(): Response
    {
        return new JsonResponse('porduct create', Response::HTTP_OK);
    }

    #[Rest\Put('/{id}', name: 'update')]
    public function updateAction(): Response
    {
        return new JsonResponse('porduct update', Response::HTTP_OK);
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
