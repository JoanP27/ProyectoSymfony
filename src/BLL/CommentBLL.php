<?php

namespace App\BLL;

use App\BLL\BaseApiBLL;
use App\Entity\Comentario;
use App\Repository\JuegoRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommentBLL extends BaseApiBLL
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly JuegoRepository $juegoRepository,
        private readonly ValidatorInterface $validator,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly UsuarioRepository $usuarioRepository,
    )
    {
        parent::__construct($this->entityManager, $this->validator, $this->tokenStorage);
    }
    public function toArray($entity): ?array
    {
        if(is_null($entity)) return null;
        if(!$entity instanceof Comentario)
            throw new \Exception("La entidad $entity no es un juego");

        return [
            'id' => $entity->getId(),
            'mensaje' => $entity->getMensaje(),
            'juego' => $entity->getJuego()->getId(),
            'emisor' => $entity->getEmisor()->getId(),
        ];
    }

    public function arrayToEntity(?array $data): ?Comentario
    {
        if(is_null($data)) return null;
        if(!is_array($data)) return null;


        $entity = new Comentario();
        $entity->setMensaje($data['mensaje']);
        $entity->setJuego($data['juego']);
        $entity->setEmisor($data['emisor']);
        return $entity;
    }

    public function getAll(int $idJuego): ?array
    {
        $juego = $this->juegoRepository->find($idJuego);
        return $this->entitiesToArray($juego->getComentarios()->toArray());
    }
    public function addComment(int $idJuego, ?array $datos): ?array {
        $juego = $this->juegoRepository->find($idJuego);
        $datos['juego'] = $juego;
        $datos['emisor'] = $this->getUsuario();
        $comentario = $this->arrayToEntity($datos);

        $this->entityManager->persist($comentario);
        $this->entityManager->flush();
        return $this->entitiesToArray([$comentario]);
    }

    public function deleteComment(int $idJuego, int $idComentario): ?array {
        $juego = $this->juegoRepository->find($idJuego);

        $comentario = array_find($juego->getComentarios()->toArray(), function ($c) use ($idComentario) {
            return $c->getId() == $idComentario;
        });

        $this->entityManager->remove($comentario);
        $this->entityManager->flush();
        return [];
    }
}
