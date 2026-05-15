<?php

declare(strict_types=1);

namespace App\Controller\api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

class BaseApiController extends AbstractController
{
    public function getContent(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if(is_null($data)) {
            throw new BadRequestHttpException('No se han recibido los datos');
        }
        return $data;
    }

    public function getResponse(?array $data, $statusCode = Response::HTTP_OK): Response
    {
        $result = [];
        $response = new JsonResponse();
        if(!is_null($data)) {
            $result['data'] = $data;
        }
        $response->setContent(json_encode($result));
        $response->setStatusCode($statusCode);

        return $response;
    }
}
