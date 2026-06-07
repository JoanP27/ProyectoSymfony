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

    public function searchJuegos(?string $titulo, ?int $categoria, ?DateTime $fechaInicio, ?DateTime $fechaFinal): ?array
    {
        $qb = $this->createQueryBuilder('Juego')
            ->where('Juego.titulo LIKE :titulo')
            ->setParameter('titulo', "%$titulo%");

        if($categoria != null)
            $qb->andWhere('Juego.categoria = :categoria')
                ->setParameter('categoria', $categoria);


        if ($fechaInicio != null)
            $qb->andWhere('Juego.fecha >= :fechaInicio')
                ->setParameter('fechaInicio', $fechaInicio);

        if ($fechaFinal != null)
            $qb->andWhere('Juego.fecha <= :fechaFinal')
                ->setParameter('fechaFinal', $fechaFinal);


        return $qb->getQuery()->getResult();
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
