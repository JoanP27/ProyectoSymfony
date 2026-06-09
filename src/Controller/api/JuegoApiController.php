<?php

namespace App\Controller\api;

use App\BLL\CommentBLL;
use App\BLL\JuegoBLL;
use App\Entity\Juego;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
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

    #[Route(
        path: "/juegos/{id}.{_format}",
        name: "get_one_juego",
        requirements: ['_format' => 'json'],
        defaults: ['_format' => 'json'],
        methods: ['GET']
    )]
    public function getOne(JuegoBLL $juegoBLL, Request $request): Response
    {
        try {
            //$datos = $request->query->all();
            $juegos = $juegoBLL->getOne($request->attributes->get('id'));
            return $this->getResponse($juegos, Response::HTTP_OK);
        } catch (\Exception $ex) {
            return $this->getResponse(["Error: " . $ex->getMessage()],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route(
        path: "/juegos.{_format}",
        name: "add_juego",
        requirements: ['_format' => 'json'],
        defaults: ['_format' => 'json'],
        methods: ['POST']
    )]
    public function addJuego(JuegoBLL $juegoBLL, Request $request): Response
    {
        try {
            $juegos = $juegoBLL->add($this->getContent($request));
            return $this->getResponse($juegos, Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            return $this->getResponse(["Error: " . $ex->getMessage()],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route(
        path: "/juegos/{id}.{_format}",
        name: "delete_juego",
        requirements: ['_format' => 'json'],
        defaults: ['_format' => 'json'],
        methods: ['DELETE']
    )]
    public function deleteJuego(JuegoBLL $juegoBLL, Request $request, int $id): Response
    {
        try {
            $juegos = $juegoBLL->delete($id);
            return $this->getResponse($juegos, Response::HTTP_OK);
        }
        catch (HttpException $ex) {
            return $this->getResponse(["Error: " . $ex->getMessage()], $ex->getStatusCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        catch (\Exception $ex) {
            return $this->getResponse(["Error: " . $ex->getMessage()],  Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route(
        path: "/juegos/{id}.{_format}",
        name: "update_juego",
        requirements: ['_format' => 'json'],
        defaults: ['_format' => 'json'],
        methods: ['PUT']
    )]
    public function update(JuegoBLL $juegoBLL, Request $request, int $id): Response
    {
        try {
            $juegos = $juegoBLL->update($id, $this->getContent($request));
            return $this->getResponse($juegos, Response::HTTP_OK);
        }
        catch (HttpException $ex) {
            return $this->getResponse(["Error: " . $ex->getMessage()], $ex->getStatusCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        catch (\Exception $ex) {
            return $this->getResponse(["Error: " . $ex->getMessage()],  Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route(
        path: "/juegos/{id}/comments.{_format}",
        name: "add_comentario",
        requirements: ['_format' => 'json'],
        defaults: ['_format' => 'json'],
        methods: ['POST']
    )]
    public function addComentario(JuegoBLL $juegoBLL, CommentBLL $commentBLL, Request $request, int $id): Response
    {
        try {
            $juegos = $commentBLL->addComment($id, $this->getContent($request));
            return $this->getResponse($juegos, Response::HTTP_OK);
        }
        catch (HttpException $ex) {
            return $this->getResponse(["Error: " . $ex->getMessage()], $ex->getStatusCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        catch (\Exception $ex) {
            return $this->getResponse(["Error: " . $ex->getMessage()],  Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route(
        path: "/juegos/{id}/comments.{_format}",
        name: "get_comentarios",
        requirements: ['_format' => 'json'],
        defaults: ['_format' => 'json'],
        methods: ['GET']
    )]
    public function getComentarios(CommentBLL $commentBLL, Request $request, int $id): Response
    {
        try {
            $juegos = $commentBLL->getAll($id);
            return $this->getResponse($juegos, Response::HTTP_OK);
        }
        catch (HttpException $ex) {
            return $this->getResponse(["Error: " . $ex->getMessage()], $ex->getStatusCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        catch (\Exception $ex) {
            return $this->getResponse(["Error: " . $ex->getMessage()],  Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route(
        path: "/juegos/{id}/comments/{idComentario}.{_format}",
        name: "delete_comentario",
        requirements: ['_format' => 'json'],
        defaults: ['_format' => 'json'],
        methods: ['DELETE']
    )]
    public function deleteComentario(CommentBLL $commentBLL, Request $request, int $id, int $idComentario): Response
    {
        try {
            $res = $commentBLL->deleteComment($id, $idComentario);
            return $this->getResponse($res, Response::HTTP_OK);
        }
        catch (HttpException $ex) {
            return $this->getResponse(["Error: " . $ex->getMessage()], $ex->getStatusCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        catch (\Exception $ex) {
            return $this->getResponse(["Error: " . $ex->getMessage()],  Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
