<?php

namespace App\Controller\api;

use App\BLL\UsuarioBLL;
use App\Controller\api\BaseApiController;
use App\Entity\Usuario;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UsuarioApiController extends BaseApiController
{
    #[Route(
        path: "/profile.{_format}",
        name: "get_profile",
        requirements: ['_format' => 'json'],
        defaults: ['_format' => 'json'],
        methods: ['GET']
    )]
    public function getProfile() {

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
