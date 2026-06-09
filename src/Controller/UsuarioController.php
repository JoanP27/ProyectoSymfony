<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UserProfileFormType;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UsuarioController extends AbstractController
{
    #[Route('/my-profile', name: 'app_my_profile')]
    public function index(): Response
    {
        return $this->render('usuario/perfil.html.twig', ['usuario' => $this->getUser()]);
    }

    #[Route('/profile/{id}', name: 'app_profile')]
    public function showProfile(int $id, UsuarioRepository $usuarioRepository): Response
    {
        $usuario = $usuarioRepository->find($id);
        if (!$usuario) {
            throw $this->createNotFoundException();
        }
        return $this->render('usuario/perfil.html.twig', ['usuario' => $usuario]);
    }

    #[Route('/my-profile-edit', name: 'app_edit_my_profile')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserProfileFormType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatar = $form->get('avatar')->getData();

            if ($avatar) {
                $avatarFile = uniqid() . '.' . $avatar->guessExtension();

                try {
                    $avatar->move($this->getParameter('avatars_directory'), $avatarFile);
                    $this->getUser()->setAvatar($avatarFile);
                } catch (FileException $e) {
                    $this->addFlash('error', 'No se pudo subir la imagen: ' . $e->getMessage());
                    return $this->redirectToRoute('app_edit_my_profile');
                }
            }

            $entityManager->flush();
            return $this->redirectToRoute('app_my_profile', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('usuario/perfil-edit.html.twig', ['usuario' => $this->getUser(), 'form' => $form->createView()]);
    }


}
