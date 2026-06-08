<?php

namespace App\Controller;

use App\Entity\Comentario;
use App\Entity\Juego;
use App\Form\ComentarioType;
use App\Form\JuegoType;
use App\Repository\CategoriaJuegoRepository;
use App\Repository\JuegoRepository;
use DateMalformedStringException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
    #[Route('/library', name: 'app_juego_library', methods: ['GET'])]
    public function library(Request $request, JuegoRepository $juegoRepository, CategoriaJuegoRepository $categoriaJuegoRepository): Response
    {
        $juegos = $this->getUser()->getJuegosComprados();
        return $this->render('juego/library.html.twig', ['juegos' => $juegos]);
    }

    #[Route('/pubished', name: 'app_juego_published', methods: ['GET'])]
    public function published(Request $request, JuegoRepository $juegoRepository, CategoriaJuegoRepository $categoriaJuegoRepository): Response
    {
        $juegos = $this->getUser()->getJuegos();
        return $this->render('juego/published.html.twig', ['juegos' => $juegos]);
    }

    #[Route('/new', name: 'app_juego_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $juego = new Juego();
        $form = $this->createForm(JuegoType::class, $juego);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $img = $form->get('rutaImagen')->getData();

            if ($img) {
                $imgFile = uniqid() . '.' . $img->guessExtension();

                try {
                    $img->move($this->getParameter('img_juegos_directory'), $imgFile);
                    $juego->setRutaImagen($imgFile);
                } catch (FileException $e) {
                    $this->addFlash('error', 'No se pudo subir la imagen: ' . $e->getMessage());
                    return $this->redirectToRoute('app_juego_new');
                }
            }

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

    #[Route('/{id}/add-comment', name: 'app_juego_add_comment', methods: ['POST'])]
    public function addComment(Request $request, Juego $juego, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comentario();
        $comment->setJuego($juego);
        $comment->setEmisor($this->getUser());
        $form = $this->createForm(ComentarioType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('app_juego_show', ['id' => $juego->getId()]);
        }
        $this->addFlash('error', 'No se pudo añadir el comentario');
        return $this->redirectToRoute('app_juego_show', ['id' => $juego->getId()]);
    }
    #[Route('/{id}', name: 'app_juego_show', methods: ['GET'])]
    public function show(Juego $juego): Response
    {
        $commentarioForm = $this->createForm(ComentarioType::class, null, [
            'action' => $this->generateUrl('app_juego_add_comment', ['id' => $juego->getId()])
        ]);

        return $this->render('juego/show.html.twig', [
            'juego' => $juego,
            'commentarioForm' => $commentarioForm->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_juego_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Juego $juego, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JuegoType::class, $juego);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $img = $form->get('rutaImagen')->getData();

            if ($img) {
                $imgFile = uniqid() . '.' . $img->guessExtension();

                try {
                    $img->move($this->getParameter('img_juegos_directory'), $imgFile);
                    $juego->setRutaImagen($imgFile);
                } catch (FileException $e) {
                    $this->addFlash('error', 'No se pudo subir la imagen: ' . $e->getMessage());
                    return $this->redirectToRoute('app_juego_new');
                }
            }

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

    #[Route('/{id}/buy', name: 'app_juego_buy', methods: ['POST'])]
    public function buy(Request $request, Juego $juego, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('buy'.$juego->getId(), $request->getPayload()->getString('_token'))) {
            $this->getUser()->addJuegosComprado($juego);
            $juego->getUsuariosVendido()->add($this->getUser());
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_juego_index', [], Response::HTTP_SEE_OTHER);
    }
}
