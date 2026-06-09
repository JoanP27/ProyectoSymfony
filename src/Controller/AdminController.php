<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_index')]
    public function index(UsuarioRepository $usuarioRepository, Request $request): Response
    {
        $rol = $request->query->get('role');
        $usuarios = $rol
            ? $usuarioRepository->findByRole($rol)
            : $usuarioRepository->findAll();
        return $this->render('admin/index.html.twig',
            ['usuarios' => $usuarios, "filtros" => ['role' => $rol]]);
    }
    #[Route('/admin/{id}/role', name: 'app_admin_changerole', methods: ['POST'])]
    public function changeRole(EntityManagerInterface $entityManager,  Usuario $usuario, Request $request): Response
    {
        $rol = $request->request->get('role');
        $usuario->setRoles([$rol]);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_index');
    }

    #[Route("/admin/{id}/delete", name: 'app_admin_delete', methods: ['POST'])]
    public function delete(EntityManagerInterface $entityManager, Usuario $usuario, Request $request): Response
    {
        $entityManager->remove($usuario);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin_index');
    }

}
