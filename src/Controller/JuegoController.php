<?php

namespace App\Controller;

use App\Entity\Juego;
use App\Form\JuegoType;
use App\Repository\CategoriaJuegoRepository;
use App\Repository\JuegoRepository;
use DateMalformedStringException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/juego')]
final class JuegoController extends AbstractController
{
    /**
     * @throws DateMalformedStringException
     */
    #[Route('', name: 'app_juego_index', methods: ['GET'])]
    public function index(Request $request, JuegoRepository $juegoRepository, CategoriaJuegoRepository $categoriaJuegoRepository): Response
    {
        $search = $request->query->get('titulo');
        $category = $request->query->get('categoria');
        $txtFechaInicio = $request->query->get('fecha_inicio');
        $txtFechaFinal = $request->query->get('fecha_final');

        $categorias = $categoriaJuegoRepository->findAll();

        $fechaInicio =
            $txtFechaInicio == '' ?
                null :
                new DateTime($txtFechaInicio);

        $fechaFinal =
            $txtFechaInicio == '' ?
                null :
                new DateTime($txtFechaFinal);

        $juegos = $juegoRepository
            ->searchJuegos(
                $search,
                $category,
                !$fechaInicio ? null : $fechaInicio,
                !$fechaFinal ? null : $fechaFinal
            );

        return $this->render('juego/index.html.twig', ['juegos' => $juegos, 'categorias' => $categorias]);
    }

    #[Route('/new', name: 'app_juego_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $juego = new Juego();
        $form = $this->createForm(JuegoType::class, $juego);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $juego->setAutor($this->getUser());

            $entityManager->persist($juego);
            $entityManager->flush();

            return $this->redirectToRoute('app_juego_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('juego/new.html.twig', [
            'juego' => $juego,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_juego_show', methods: ['GET'])]
    public function show(Juego $juego): Response
    {
        return $this->render('juego/show.html.twig', [
            'juego' => $juego,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_juego_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Juego $juego, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JuegoType::class, $juego);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_juego_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('juego/edit.html.twig', [
            'juego' => $juego,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_juego_delete', methods: ['POST'])]
    public function delete(Request $request, Juego $juego, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$juego->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($juego);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_juego_index', [], Response::HTTP_SEE_OTHER);
    }
}
