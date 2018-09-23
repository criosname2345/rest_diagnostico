<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class DiagnosticosController extends ControllerBase
{

    public function indexAction()
    {

    }

    public function crear_diagnostico(){
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

        $diagnostico = new diag\cc\Diagnostico();
        $diagnostico->total_preguntas = $json->total_preguntas;

        //traer camara de comercio del usuario
        $us_ses = $this->session->get('usuario');

        if(!isset($us_ses['id_contacto'])){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'Usuario no contiene contacto',
                    'contacto' => $us_ses['id_contacto'],
                ]
            );
            return $response;
        }
        
        $contacto = diag\cc\Contacto::findfirst(['id_contacto = ?0',
        'bind' => [ $us_ses['id_contacto'] ],]);

        $empresa = diag\cc\Empresa::findfirst(['id_empresa = ?0',
        'bind' => [ $contacto->id_empresa ],]);

        if($empresa === false){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'No existe la empresa con id'.$contacto->id_empresa,
                ]
            );
            return $response;
        }
        //Si la empresa no es camara de comercio no puede crear diagnostico
        if($empresa->es_cc != 1){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'La empresa '.$empresa->razon_social.' no es camara de comercio',
                ]
            );
            return $response;            
        }
        //Si la camara de comercio ya tiene diagnostico no puede crear
        if($empresa->id_diagnostico != null){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'La empresa '.$empresa->razon_social.' ya tiene un diagnostico',
                ]
            );
            return $response;            
        }        

        $diagnostico->id_empresa = $empresa->id_empresa;

        if ($diagnostico->create() === false) {
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'No se ha podido crear el diagnostico',
                    'empresa'   => $empresa,
                    'diagnostico' => $diagnostico,
                ]
            );           
        }else{

            $empresa->id_diagnostico = $diagnostico->id_diagnostico;
            $empresa->update();
            $response->setJsonContent(
                [
                    'status'   => 'OK',
                    'messages' => 'Se registro el diagnostico correctamente',
                    'diagnostico' => $diagnostico,
                    'empresa'   => $empresa,
                ]
            );              
        }     
        
        return $response;        
        
    }

    public function grabar_pregunta(){
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
        
        $pregunta = new diag\cc\Pregunta();
        $pregunta->tipo = $json->tipo;
        $pregunta->txt_pregunta = $json->txt_pregunta;
        if(!isset($json->id_diagnostico) || diag\cc\Diagnostico::findfirst($json->id_diagnostico) === false ){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'Diagnostico no existe, id_diagnostico: '.$json->id_diagnostico,
                ]
            );
            return $response;            
        }
        $pregunta->id_diagnostico = $json->id_diagnostico;
        if(!isset($json->id_categoria) || diag\cc\Categoria::findfirst($json->id_categoria) === false ){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'Categoria no existe, id_categoria: '.$json->id_categoria,
                ]
            );
            return $response;            
        }        
        $pregunta->id_categoria = $json->id_categoria;

        if ($pregunta->create() === false) {
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'No se ha podido grabar la pregunta',
                    'pregunta'   => $pregunta,
                ]
            );           
        }else{

            $response->setJsonContent(
                [
                    'status'   => 'OK',
                    'messages' => 'Se registro correctamente la pregunta',
                    'pregunta'   => $pregunta,
                ]
            );              
        }     
        
        return $response;    
    }

    public function grabar_respuestas(){
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

        $respuesta = new diag\cc\OpcRespuesta();
        //Mirar que tipo de pregunta es y gestionar
        $pregunta = diag\cc\Pregunta::findfirst(['id_pregunta = ?0',
        'bind' => [ $json->id_pregunta ],]);

        $respuesta->id_pregunta = $pregunta->id_pregunta;
        switch($pregunta->tipo){
            case '1':

                break;
            case '2':

                break;
            default:
                // Cambiar el HTTP status
                $response->setStatusCode(409, 'Conflict');
                $response->setJsonContent(
                    [
                        'status'   => 'ERROR',
                        'messages' => 'No existe el tipo de pregunta: '.$pregunta->tipo,
                    ]
                );
                return $response;
        }
    }

    public function listar_preguntas(){
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

        $preguntas = diag\cc\Pregunta::find(['id_diagnostico = ?0',
        'bind' => [ $json->id_diagnostico ],]);

        if(!isset($json->id_diagnostico) || $preguntas === false ){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'No existen preguntas para el id_diagnostico '.$json->id_diagnostico ,
                ]
                ); 
            return $response;
        }

        $respuestas = array();

        foreach($preguntas as $pregunta){

            $respuestas[$pregunta->id_pregunta] = diag\cc\OpcRespuesta::find(['id_pregunta = ?0',
            'bind' => [ $pregunta->id_pregunta ],]);

        }

        $response->setJsonContent(
            [
                'status'   => 'OK',
                'messages' => 'Preguntas diagnostico '.$json->id_diagnostico ,
                'Preguntas' => $preguntas ,
                'Respuestas' => $respuestas ,
            ]
            );         
                                              
        return $response;

    }

    public function realizar_intento(){
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
        
        $intento = new diag\cc\Intento();
        $intento->fecha = date("Y-m-d");
        if($json->resultado > 100){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'Resultado no puede ser mayor que 100',
                ]
            );
            return $response;
        }       
        $intento->resultado = $json->resultado;
        //traer camara de comercio del usuario
        $us_ses = $this->session->get('usuario');

        if(!isset($us_ses['id_contacto'])){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'Usuario no contiene contacto',
                ]
            );
            return $response;
        }

        $contacto = diag\cc\Contacto::findfirst(['id_contacto = ?0',
        'bind' => [ $us_ses['id_contacto'] ],]);

        $empresa_usuario = diag\cc\Empresa::findfirst(['id_empresa = ?0',
        'bind' => [ $contacto->id_empresa ],]);

        //Si la empresa no es camara de comercio no puede crear intento diagnostico
        if($empresa_usuario->es_cc != 1){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'La empresa '.$empresa->razon_social.' no es camara de comercio',
                ]
            );
            return $response;            
        }
        if($empresa_usuario->id_diagnostico == null){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'La empresa '.$empresa->razon_social.' no tiene diagnostico',
                ]
            );
            return $response;            
        }

        $intento->id_diagnostico = $empresa_usuario->id_diagnostico;
        $intento->id_empresa =  $empresa_usuario->id_empresa;

        if ($intento->create() === false) {
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'No se ha podido grabar el intento',
                    'intento'   => $intento,
                ]
            );           
        }else{

            $response->setJsonContent(
                [
                    'status'   => 'OK',
                    'messages' => 'Se registro correctamente el intento',
                    'intento'   => $intento,
                ]
            );              
        }     
        
        return $response;        

    }

}

