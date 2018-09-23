<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class ContactosController extends ControllerBase
{

    public function indexAction()
    {

    }

    public function crear_contacto(){

        // Crear una respuesta
        $response = new Response();
        if ($this->request->isPost()) {
             $json = $this->request->getJsonRawBody();
             $loger = $this->validar_logueo($json->token);
             if (!$loger){
                 // Cambiar el HTTP status
                 $response->setStatusCode(409, 'Conflict');
                 $response->setJsonContent(
                     [
                         'status'   => 'ERROR',
                         'messages' => 'Usuario no ha sido autenticado',
                     ]
                 );
                 return $response;
            }
        }else{
             $response->setStatusCode(404, 'Not Found');
             return $response;
        } 

        $contacto = new diag\cc\Contacto();
        $contacto->documento = $json->documento;
        $contacto->nombre    = $json->nombre;
        $contacto->p_apellido = $json->p_apellido;
        $contacto->s_apellido = $json->s_apellido;
        $contacto->tipo_doc = $json->tipo_doc;
        $contacto->correo = $json->correo;
        $contacto->direccion = $json->direccion;
        $contacto->pais = $json->pais;

    }

}

