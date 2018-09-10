<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class EmpresasController extends ControllerBase
{

    public function indexAction()
    {

    }

    public function listar_empresas(){

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

    }

    public function crear_empresa(){

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


        $empresa =  new diag\cc\Empresa();
        $empresa->razon_social = $json->razon_social;
        $empresa->nit          = $json->nit;
        $empresa->afiliacion = $json->afiliacion;
        $empresa->web = $json->web;
        $empresa->repr_legal = $json->repr_legal;
        $empresa->ger_general = $json->ger_general;
        $empresa->direccion = $json->direccion;
        $empresa->constitucion = $json->constitucion;
        $empresa->ccit = $json->ccit;
        $empresa->es_cc = '1';
        $empresa->web = $json->web;
        $empresa->web = $json->web;
        
    }


}

