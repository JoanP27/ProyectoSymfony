<?php

namespace App\Repository;

use App\Entity\Juego;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Juego>
 */
class JuegoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Juego::class);
    }

    public function searchJuegos(?string $titulo, ?int $categoria, ?float $precio, ?DateTime $fechaInicio, ?DateTime $fechaFinal): ?array {
        return $this->createQueryBuilder('Juego')
            ->where('Juego.titulo LIKE :titulo')
            ->andWhere('Juego.precio = :precio')
            ->andWhere('Juego.fecha > :fechaInicio')
            ->andWhere('Juego.fecha < :fechaFinal')
            ->setParameter('titulo', "%$titulo%")
            ->setParameter('precio', $precio)
            ->setParameter('fechaInicio', $fechaInicio)
            ->setParameter('fechaFinal', $fechaFinal)
            ->getQuery()->getResult();
/*

        $qb = $this->createQueryBuilder('Juego');

        if(empty($filtros)) return [];

        foreach ($filters as $key => $value) {
            $qb->where(':key LIKE :valor');
            $qb->setParameter('valor', "%$value%");
            $qb->setParameter('key', "Juego.$key");
        }
        return $this->createQueryBuilder('Juego')
            ->where('Juego.titulo LIKE :titulo');*/
    }

//    /**
//     * @return Juego[] Returns an array of Juego objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Juego
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
