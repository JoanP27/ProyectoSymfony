<?php

namespace App\BLL;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseApiBLL
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator,
    ) { }

    public function guardarValidando($entity): ?array {
        $errors = $this->validator->validate($entity);

        if(count($errors) > 0) {
            throw new BadRequestException('Errores: ' . $errors);
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush($entity);

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
}
