<?php

namespace App\BLL;

use App\BLL\BaseApiBLL;
use App\Entity\Juego;
use App\Repository\JuegoRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JuegoBLL extends BaseApiBLL
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly JuegoRepository $juegoRepository,
        private readonly ValidatorInterface $validator,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly UsuarioRepository $usuarioRepository
    )
    {
        parent::__construct($this->entityManager, $this->validator, $this->tokenStorage);
    }
    /*
     * Devuelve un array con los datos del juego guardado
     */
    public function nuevo(array $datos) {
        $juego = new Juego();

        $juego->setTitulo($datos['titulo']);
        $juego->setDescripcion($datos['descripcion']);
        $juego->setFecha($datos['fecha']);
        $juego->setPrecio($datos['precio']);
        $juego->setRutaImagen($datos['rutaImagen']);

        return $this->guardarValidando($juego);
    }
    public function editar(Juego $juego, array $datos): ?array {
        $juego->setTitulo($datos['titulo']);
        $juego->setDescripcion($datos['descripcion']);
        $juego->setFecha($datos['fecha']);
        $juego->setPrecio($datos['precio']);
        $juego->setRutaImagen($datos['rutaImagen']);

        return $this->guardarValidando($juego);
    }
    public function toArray($entity): ?array
    {
        if(is_null($entity)) return null;
        if(!$entity instanceof Juego)
            throw new Exception("La entidad $entity no es un juego");

        return [
            'id' => $entity->getId(),
            'titulo' => $entity->getTitulo(),
            'descripcion' => $entity->getDescripcion(),
            'fecha' => $entity->getFecha(),
            'precio' => $entity->getPrecio(),
            'rutaImagen' => $entity->getRutaImagen()
        ];

    }

    public function arrayToEntity(?array $datos, ?Juego $juegoAntiguo = null): ?Juego {
        if(is_null($datos)) return null;
        if(!is_array($datos)) return null;

        $juego = $juegoAntiguo != null ? $juegoAntiguo : new Juego();

        $juego->setTitulo($datos['titulo']);
        $juego->setDescripcion($datos['descripcion']);
        if(isset($datos['fecha'])) {
            $juego->setFecha(\DateTime::createFromFormat('Y-m-d', $datos['fecha']));
        }
        $juego->setPrecio($datos['precio']);
        $juego->setRutaImagen($datos['rutaImagen']);

        if (isset($datos['autor_id'])) {
            $autor = $this->usuarioRepository->find($datos['autor_id']);
            $juego->setAutor($autor);
        }
        return $juego;
    }

    /**
     * @throws Exception
     */
    public function getAll(): ?array
    {
        $juegos = $this->juegoRepository->findAll();
        return $this->entitiesToArray($juegos);
    }

    public function getOne(int $id): ?array
    {
        $juegos = $this->juegoRepository->find(['id' => $id]);
        return $this->entitiesToArray([$juegos]);
    }

    /**
     * @throws Exception
     */
    public function add(?array $datos): ?array
    {
        $juego = $this->arrayToEntity($datos);
        if($juego == null) throw new Exception("Formato de juego incorrecto");

        $this->entityManager->persist($juego);
        $this->entityManager->flush();
        return $this->entitiesToArray([$juego]);
    }

    /**
     * @throws Exception
     */
    public function delete(int $id): ?array
    {
        $juego = $this->juegoRepository->find(['id' => $id]);

        if($juego == null) throw new HttpException(404, "Juego no encontrado");
        if($juego->getComentarios() != null) {
            foreach ($juego->getComentarios() as $comentario) {
                $this->entityManager->remove($comentario);
            }
        }

        $this->entityManager->remove($juego);
        $this->entityManager->flush();
        return [];
    }

    public function update(int $id, ?array $datos): ?array
    {
        $juego = $this->juegoRepository->find(['id' => $id]);
        $juego = $this->arrayToEntity($datos, $juego);

        if($juego == null) throw new HttpException(404, "Juego no encontrado");

        $this->entityManager->flush();
        return $this->entitiesToArray([$juego]);
    }

    public function addComment(int $id): ?array
    {

    }
}

