<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class VisitasController extends ControllerBase
{

    public function obt_visitas()
    {
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
        
        $Visitas = diag\cc\Visitas::findFirst(['id_visita = ?0',
        'bind' => [ $json->id_visita ],]);

        if($Visitas === false){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'No existe la visita con id'.$json->id_visita ,
                ]
                ); 
            return $response;
        }

        $response->setJsonContent(
            [
                'status'   => 'OK',
                'messages' => 'Información Visita',
                'Visita' => $Visitas ,
            ]
            );         
                                              
        return $response;
    }

    public function crear_visita(){

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

        $visita =  new diag\cc\Visita();
        $visita->fecha = date("Y-m-d");
        $visita->comentario;
        //Verificar Empresa que exista
        if(isset($json->id_empresa)){
            $Empresa = diag\cc\Empresa::findFirst(['id_visita = ?0',
            'bind' => [ $json->id_empresa ],]);
            if($Empresa === false){
                $response->setStatusCode(409, 'Conflict');
                $response->setJsonContent(
                    [
                        'status'   => 'ERROR',
                        'messages' => 'No existe la empresa con id'.$json->id_empresa,
                        'err_syl'  => $json->id_empresa,
                    ]
                );
                return $response;
            }
            $visita->id_empresa = $Empresa->id_empresa ;
        }

        $usuario = $this->session->get('usuario');

        $visita->id_usuario = $usuario->id_usuario;

        $Categoria = diag\cc\Categoria::findFirst(['id_categoria = ?0',
        'bind' => [ $json->id_categoria ],]);
        if($Categoria === false){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'No existe la categoria con id'.$json->id_categoria,
                    'err_syl'  => $json->id_categoria,
                ]
            );
            return $response;
        }

        $visita->id_categoria = $Categoria->id_categoria;



    }

}

