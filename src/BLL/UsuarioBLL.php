<?php

namespace App\BLL;

use App\BLL\BaseApiBLL;
use Symfony\Component\HttpFoundation\Request;

class UsuarioBLL extends BaseApiBLL
{
    public function profile() {

    }

    public function toArray($entity): ?array
    {
        return [];
        // TODO: Implement toArray() method.
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
