<?php

declare(strict_types=1);

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[Route(path: "/api/products", name: "api_products-")]
class HealthCheckController extends AbstractFOSRestController
{
    #[Rest\Get('', name: 'list')]
    public function healthCheckAction(): Response
    {
        return new JsonResponse('porduct list', Response::HTTP_OK);
    }

    #[Rest\Post('', name: 'create')]
    public function createAction(): Response
    {
        return new JsonResponse('porduct list', Response::HTTP_OK);
    }

    #[Rest\Put('/{id}', name: 'update')]
    public function updateAction(): Response
    {
        return new JsonResponse('porduct list', Response::HTTP_OK);
    }

    #[Rest\Patch('/{id}', name: 'patch')]
    public function patchAction(): Response
    {
        return new JsonResponse('porduct list', Response::HTTP_OK);
    }

    #[Rest\Delete('/{id}', name: 'delete')]
    public function deleteAction(): Response
    {
        return new JsonResponse('porduct list', Response::HTTP_OK);
    }

    #[Rest\Delete('/{id}/remove', name: 'remove')]
    public function removeAction(): Response
    {
        return new JsonResponse('porduct list', Response::HTTP_OK);
    }
}
