<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class ContactosController extends ControllerBase
{

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
        $contacto->correo = $json->correocontacto;
        $contacto->direccion = $json->direccion;
        $contacto->pais = $json->pais;
        $contacto->depto = $json->depto;
        $contacto->mcipio = $json->mcipio;
        $contacto->celular = $json->celular;
        $contacto->fijo = $json->fijo;
        $contacto->genero = $json->genero;
        $contacto->fec_nacimiento = $json->fec_nacimiento;
        $contacto->nivel_estudio = $json->nivel_estudio;
        $contacto->ocupacion = $json->ocupacion;
        $contacto->cargo = $json->cargo;
        $contacto->id_empresa = $json->id_empresa;

        if ($contacto->create() === false) {
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'No se ha podido crear el contacto',
                    'contacto'   => $contacto,
                ]
            );           
        }else{

            $response->setJsonContent(
                [
                    'status'   => 'OK',
                    'messages' => 'Se registro correctamente el contacto',
                    'contacto' => $contacto,
                ]
            );              
        }     
        
        return $response;          

    }

    public function listar_contactos(){

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
         
        $contactos = diag\cc\Contacto::find(['id_empresa = ?0',
        'bind' => [ $json->id_empresa ],]);

        $response->setJsonContent(
            [
                'status'   => 'OK',
                'messages'    => 'Contactos para la empresa '.$json->id_empresa,
                'contactos'   => $contactos,
            ]
        );           
        
        return $response;  

    }

}

