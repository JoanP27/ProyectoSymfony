<?php

namespace App\BLL;

use App\Repository\JuegoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseApiBLL
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator,
        private readonly TokenStorageInterface $tokenStorage,
    ) { }

    public function guardarValidando($entity): ?array {
        $errors = $this->validator->validate($entity);

        if(count($errors) > 0) {
            throw new BadRequestException('Errores: ' . $errors);
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $this->toArray($entity);
    }
    public abstract function toArray($entity): ?array;

    public function entitiesToArray(?array $entities): ?array {
        if(!is_array($entities)) { return null; }
        $result = [];

        foreach($entities as $entity) {
            $result[] = $this->toArray($entity);
        }

        return $result;
    }

    public function getUsuario() {
        return $this->tokenStorage->getToken()->getUser();
    }

}
