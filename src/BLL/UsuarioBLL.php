<?php

namespace App\BLL;

use App\BLL\BaseApiBLL;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Encoder\EncoderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UsuarioBLL extends BaseApiBLL
{

    public function __construct(
        public readonly EntityManagerInterface $entityManager,
        public readonly ValidatorInterface $validator,
        public readonly TokenStorageInterface $tokenStorage,
        public readonly UserPasswordHasherInterface $hasher
    )
    {
        parent::__construct($this->entityManager, $this->validator, $this->tokenStorage);
    }
    public function cambiaAvatar(Request $request, $avatar, $avatars_directory, $url_avatars_directory)
    {
        $user = $this->getUsuario();
        $arr_avatar = explode(',', $avatar);
        if (!str_contains($arr_avatar[0], 'image/jpeg') && !str_contains($arr_avatar[0], 'image/jpg')) {
            throw new BadRequestException('Formato no permitido. Solo se aceptan imágenes JPG.');
        }
        if (count($arr_avatar) < 2)
            throw new BadRequestException('formato de imagen incorrecto');
        $imgAvatar = base64_decode($arr_avatar[1]);
        if (!is_null($imgAvatar))
        {
            $fileName = $user->getNombre().'-'.time().'.jpg';
            $filePath = $url_avatars_directory . $fileName;
            $urlAvatar = $request->getUriForPath($filePath);
            $user->setAvatar($fileName);
            $ifp = fopen($avatars_directory . "/" . $fileName, "wb");
            if ($ifp)
            {
                $ok = fwrite($ifp, $imgAvatar);
                if ($ok)
                    return $this->guardarValidando($user);
                fclose($ifp);
            }
        }
        throw new \Exception('No se ha podido cargar la imagen del avatar');
    }

    public function toArray($entity): ?array
    {
        return [
            'id' => $entity->getId(),
            'email' => $entity->getEmail(),
            'password' => $entity->getPassword(),
            'nombre' => $entity->getNombre(),
            'avatar' => $entity->getAvatar()
        ];
    }

    public function nuevo(?array $datos) {
        $user = new Usuario();
        $user->setNombre($datos['username']);
        $user->setPassword($this->hasher->hashPassword($user, $datos['password']));
        $user->setEmail($datos['email']);
        $user->setAvatar('-');
        return $this->guardarValidando($user);
    }
    public function cambiarAvatar(Request $request, string $avatar, string $avatar_directory, string $url_avatar_directory)
    {
        $user = $this->getUsuario();
        $arr_avatar = explode(",", $avatar);

        if(count($arr_avatar) > 2) {
            throw new \Exception("el avatar avarar tiene que tener al menos dos caracteres");
        }
        $imgAvatar = base64_decode($arr_avatar[1]);

        if(!is_null($imgAvatar)) {
            $filename = $user->getUsername().'-'.time().'.jpg';
            $filepath = $url_avatar_directory.$filename;
            $urlAvatar = $request->getUriForPath($filepath);
            $user->setFilePath($urlAvatar);
            $ifp = fopen($filepath, 'wb');
            //...
        }
    }
}
