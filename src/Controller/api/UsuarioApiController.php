<?php

namespace App\Controller\api;

use App\BLL\UsuarioBLL;
use App\Controller\api\BaseApiController;
use App\Entity\Usuario;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/v1")]
class UsuarioApiController extends BaseApiController
{
    #[Route(
        path: "/register.{_format}",
        name: "register",
        requirements: ['_format' => 'json'],
        defaults: ['_format' => 'json'],
        methods: ['POST']
    )]
    public function register(Request $request, UsuarioBLL $userBLL)
    {
        $data = $this->getContent($request);
        $user = $userBLL->nuevo($data);
        return $this->getResponse($user, Response::HTTP_CREATED);
    }

    #[Route(
        path: "/profile.{_format}",
        name: "get_profile",
        requirements: ['_format' => 'json'],
        defaults: ['_format' => 'json'],
        methods: ['GET']
    )]
    public function getProfile(UsuarioBLL $userBLL) {
        try {
            $usuario = $userBLL->entitiesToArray([$userBLL->getUsuario()]);
            return $this->getResponse($usuario, Response::HTTP_OK);
        } catch (\Exception $ex) {
            return $this->getResponse(["Error: " . $ex->getMessage()],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route(
        path: "/profile/avatar.{_format}",
        name: "cambia_profile_avatar",
        requirements: ['_format' => 'json'],
        defaults: ['_format' => 'json'],
        methods: ['PATCH']
    )]
    public function cambiaAvatar(Request $request, UsuarioBLL $usuarioBLL) {
        $data = $this->getContent($request);
        //$avatar_directory =
    }
}
