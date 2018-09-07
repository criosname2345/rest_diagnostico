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
            'auth',
            [
                'id'      => $usuario->id_usuario,
                'correo'  => $usuario->correo,
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
            // Obtener datos desde el usuario
            // $correo    = $this->request->getPost('email');
            // $password = $this->request->getPost('password');

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

                // $this->flash->success(
                //     'Bienvenido ' . $usuario->name
                // );

                // Enviar al controlador 'invoices' si el usuario es válido
                // return $this->dispatcher->forward(
                //     [
                //         'controller' => 'invoices',
                //         'action'     => 'index',
                //     ]
                // );
                $response->setJsonContent(
                    [
                        'status'   => 'OK',
                        'messages' => 'Usuario autenticado',
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

            // $this->flash->error(
            //     'Email/Contraseña incorrectos'
            // );
        }

        // Enviar al formulario de inicio de sesión nuevamente
        // return $this->dispatcher->forward(
        //     [
        //         'controller' => 'session',
        //         'action'     => 'index',
        //     ]
        // );
    }


}

