<?php

namespace App\BLL;

use App\BLL\BaseApiBLL;
use App\Entity\Juego;
use App\Repository\JuegoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JuegoBLL extends BaseApiBLL
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly JuegoRepository $juegoRepository,
        private readonly ValidatorInterface $validator
    )
    {
        parent::__construct($this->entityManager, $this->validator);
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
            'titulo' => $entity->getTitulo(),
            'descripcion' => $entity->getDescripcion(),
            'fecha' => $entity->getFecha(),
            'precio' => $entity->getPrecio(),
            'rutaImagen' => $entity->getRutaImagen()
        ];

    }

    /**
     * @throws Exception
     */
    public function getAll(): ?array
    {
        $juegos = $this->juegoRepository->findAll();
        return $this->toArray($juegos);
    }
}
