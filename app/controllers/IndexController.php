<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class IndexController extends ControllerBase
{

    public function indexAction()
    {

    }

    private function _registerSession($usuario)
    {
        $this->session->set(
            'usuario',
            [
                'id'          => $usuario->id_usuario,
                'correo'      => $usuario->correo,
                'rol'         => $usuario->id_rol,
                'id_contacto' => $usuario->id_contacto,
            ]
        );
    }

    /**
     * Esta acción autentica y registra a un usuario dentro de la aplicación
     */
    public function autenticar()
    {
        // Crear una respuesta
        $response = new Response();
        if ($this->request->isPost()) {

            $json = $this->request->getJsonRawBody();

            $correo    = $json->correo;
            $password  = $json->contrasena;

            // Buscar el usuario en la base de datos
            $usuario = diag\cc\Usuario::findFirst(
                [
                    "correo = :correo: AND contrasena = :contrasena:",
                    'bind' => [
                        'correo'    => $correo,
                        // 'contrasena' => sha1($password),
                        'contrasena' => $password,
                    ]
                ]
            );

            if ($usuario !== false) {
                $this->_registerSession($usuario);

                $response->setJsonContent(
                    [
                        'status'   => 'OK',
                        'messages' => 'Usuario autenticado',
                        'usuario'  => $usuario,
                    ]
                );
                return $response;
            }

            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'Usuario no ha sido autenticado',
                ]
            );
            return $response;

        }

    }


}

