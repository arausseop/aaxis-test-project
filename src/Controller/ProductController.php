<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Product\ProductManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(path: "/api/products", name: "api_products-")]
class ProductController extends AbstractFOSRestController
{
    #[Rest\Get('', name: 'list')]
    public function healthCheckAction(
        ProductManager $productManager,
        SerializerInterface $serializer,
        Request $request,
    ): Response {

        $products =  $productManager->getRepository()->findAll();
        $data = $serializer->serialize($products, 'json', ['groups' => ['products-list']]);
        return JsonResponse::fromJsonString($data, Response::HTTP_OK);
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
    public function deleteAction(): Response
    {
        return new JsonResponse('porduct soft deleted', Response::HTTP_OK);
    }

    #[Rest\Delete('/{id}/remove', name: 'remove')]
    public function removeAction(): Response
    {
        return new JsonResponse('porduct phisical delete', Response::HTTP_OK);
    }
}
