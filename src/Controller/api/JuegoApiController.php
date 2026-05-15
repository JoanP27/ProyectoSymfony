<?php

namespace App\Controller\api;

use App\BLL\JuegoBLL;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/v1")]
class JuegoApiController extends BaseApiController
{
    #[Route(
        path: "/juegos.{_format}",
        name: "get_juegos",
        requirements: ['_format' => 'json'],
        defaults: ['_format' => 'json'],
        methods: ['GET']
    )]
    public function getAll(JuegoBLL $juegoBLL, Request $request): Response
    {
        try {
            //$datos = $request->query->all();
            $juegos = $juegoBLL->getAll();
            return $this->getResponse($juegos, Response::HTTP_OK);
        } catch (\Exception $ex) {
            return $this->getResponse(["Error: " . $ex->getMessage()],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
