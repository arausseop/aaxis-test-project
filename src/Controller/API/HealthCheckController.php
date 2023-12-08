<?php

declare(strict_types=1);

namespace App\Controller\API;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[Route(path: "/api/health-check", name: "api_health-check-")]
class HealthCheckController extends AbstractFOSRestController
{
    #[Rest\Get('', name: 'list')]
    public function healthCheck(): Response
    {
        return new JsonResponse('health check online', Response::HTTP_OK);
    }
}
