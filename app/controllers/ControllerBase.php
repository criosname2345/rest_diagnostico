<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function validar_logueo($token = null) {

        $respuesta = false;

        $usuario = $this->session->get('usuario');

        if (isset($usuario)) {                     
            $respuesta = true;
        }

        if(!$respuesta){
            $this->session->remove('usuario');
            // Destruye toda la sesión
            $this->session->destroy();
        }

        return $respuesta;

    }

    public function obtener_empresa(){
        //traer empresa del usuario
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

        return $empresa_usuario;
    }
        //Traer id_diagnostico teniendo ya la camara de comercio
    public function obtener_diagnostico($camara_comercio){

        $Camcom = diag\cc\Empresa::findfirst(['id_empresa = ?0',
        'bind' => [ $camara_comercio ],]);

        return $Camcom->id_diagnostico;
    }

    public function obtener_rol(){

        //traer rol del usuario
        $us_ses = $this->session->get('usuario');

        return $us_ses['rol'] ;

    }

    public function obtener_preguntas_listar($id_diagnostico){

        $preguntas = diag\cc\Pregunta::find(['id_diagnostico = ?0',
        'bind' => [ $id_diagnostico ],]);

        $respuestas = array();

        foreach($preguntas as $pregunta){
            $respuestas[] = [
            'id_pregunta'    => $pregunta->id_pregunta ,
            'tipo'           => $pregunta->tipo ,
            'txt_pregunta'   => $pregunta->txt_pregunta ,
            'id_diagnostico' => $pregunta->id_diagnostico ,
            'respuestas' => diag\cc\OpcRespuesta::find(['id_pregunta = ?0',
            'bind' => [ $pregunta->id_pregunta ],]), ];
        }

        return $respuestas;
    }

}
