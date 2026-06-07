<?php

namespace App\Controller;

use App\Entity\CategoriaJuego;
use App\Form\CategoriaJuegoType;
use App\Repository\CategoriaJuegoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/categoria/juego')]
final class CategoriaJuegoController extends AbstractController
{
    #[Route(name: 'app_categoria_juego_index', methods: ['GET'])]
    public function index(CategoriaJuegoRepository $categoriaJuegoRepository): Response
    {
        return $this->render('categoria_juego/index.html.twig', [
            'categoria_juegos' => $categoriaJuegoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categoria_juego_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categoriaJuego = new CategoriaJuego();
        $form = $this->createForm(CategoriaJuegoType::class, $categoriaJuego);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categoriaJuego);
            $entityManager->flush();

            return $this->redirectToRoute('app_categoria_juego_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categoria_juego/new.html.twig', [
            'categoria_juego' => $categoriaJuego,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categoria_juego_show', methods: ['GET'])]
    public function show(CategoriaJuego $categoriaJuego): Response
    {
        return $this->render('categoria_juego/show.html.twig', [
            'categoria_juego' => $categoriaJuego,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categoria_juego_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategoriaJuego $categoriaJuego, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoriaJuegoType::class, $categoriaJuego);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_categoria_juego_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categoria_juego/edit.html.twig', [
            'categoria_juego' => $categoriaJuego,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categoria_juego_delete', methods: ['POST'])]
    public function delete(Request $request, CategoriaJuego $categoriaJuego, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoriaJuego->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($categoriaJuego);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categoria_juego_index', [], Response::HTTP_SEE_OTHER);
    }
}
