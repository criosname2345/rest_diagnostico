<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class VisitasController extends ControllerBase
{

    public function obt_visita()
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
        
        $Visita = diag\cc\Visita::findFirst(['id_visita = ?0',
        'bind' => [ $json->id_visita ],]);

        if($Visita === false){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'No existe la visita con id '.$json->id_visita ,
                ]
                ); 
            return $response;
        }

        $response->setJsonContent(
            [
                'status'   => 'OK',
                'messages' => 'Información Visita',
                'Visita' => $Visita ,
            ]
            );         
                                              
        return $response;
    }

    public function obt_visitas(){
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

        $visitas = diag\cc\Visita::find();

        $response->setJsonContent(
            [
                'status'   => 'OK',
                'messages' => 'Información Visitas',
                'Visita'   => $visitas ,
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
        $visita->comentario = $json->comentario;
        //Verificar Empresa que exista
        if(isset($json->id_empresa)){
            $Empresa = diag\cc\Empresa::findFirst(['id_empresa = ?0',
            'bind' => [ $json->id_empresa ],]);
            if($Empresa === false){
                $response->setStatusCode(409, 'Conflict');
                $response->setJsonContent(
                    [
                        'status'   => 'ERROR',
                        'messages' => 'No existe la empresa con id '.$json->id_empresa,
                        'err_syl'  => $json->id_empresa,
                    ]
                );
                return $response;
            }
            $visita->id_empresa = $Empresa->id_empresa ;
        }else{
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'No esta pasando el id de la empresa',
                    'err_syl'  => $json->id_empresa,
                ]
            );
            return $response;
        }

        $us_ses = $this->session->get('usuario');

        $visita->id_usuario = $us_ses['id'];

        $categoria = diag\cc\Categoria::findFirst(['id_categoria = ?0',
        'bind' => [ $json->id_categoria ],]);
        if($categoria === false){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'No existe la categoria con id '.$json->id_categoria,
                    'err_syl'  => $json->id_categoria,
                ]
            );
            return $response;
        }

        $visita->id_categoria = $categoria->id_categoria;

        if ($visita->create() === false) {
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'No se ha podido crear la visita',
                    'visita'   => $visita,
                ]
            );           
        }else{
            $response->setJsonContent(
                [
                    'status'   => 'OK',
                    'messages' => 'Se creo la visita',
                    'visita'   => $visita,
                ]
            );              
        }

        return $response;

    }

    public function listar_categorias(){

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

        $categorias = diag\cc\Categoria::find( );

        $response->setJsonContent(
            [
                'status'   => 'OK',
                'messages' => 'Listado de categorias Registradas',
                'categorias'   => $categorias,
            ]
        );    

        return $response;

    }

}

