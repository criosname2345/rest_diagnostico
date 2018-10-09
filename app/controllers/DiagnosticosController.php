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

            $respuestas[] = [
            'id_pregunta'    => $pregunta->id_pregunta ,
            'tipo'           => $pregunta->tipo ,
            'txt_pregunta'   => $pregunta->txt_pregunta ,
            'id_diagnostico' => $pregunta->id_diagnostico ,
            'respuestas' => diag\cc\OpcRespuesta::find(['id_pregunta = ?0',
            'bind' => [ $pregunta->id_pregunta ],]), ];

        }

        $response->setJsonContent(
            [
                'status'   => 'OK',
                'messages' => 'Preguntas diagnostico '.$json->id_diagnostico ,
                'Preguntas' => $respuestas ,
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

        // //Si la empresa no es camara de comercio no puede crear intento diagnostico
        // if($empresa_usuario->es_cc != 1){
        //     $response->setStatusCode(409, 'Conflict');
        //     $response->setJsonContent(
        //         [
        //             'status'   => 'ERROR',
        //             'messages' => 'La empresa '.$empresa->razon_social.' no es camara de comercio',
        //         ]
        //     );
        //     return $response;            
        // }
        $CamCom = diag\cc\Empresa::findfirst($empresa_usuario->camara_comercio);
        //Validar que la camara de comercio de la empresa si tenga diagnostico
        if($CamCom->id_diagnostico == null){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'La camara de comercio '.$CamCom->razon_social.' no tiene diagnostico',
                ]
            );
            return $response;            
        }

        $intento->id_diagnostico = $empresa_usuario->id_diagnostico;
        $intento->id_empresa =  $json->id_empresa;

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

    public function grabar_resp_intento(){
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

        $intento = diag\cc\Intento::findfirst(['id_intento = ?0',
        'bind' => [ $json->id_intento ],]);
        if($json->id_intento === null || $intento === false ){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'no existe el intento '.$json->id_intento,
                ]
            );
            return $response;            
        }
        
        $respuestas_grabadas = array();
        $respuestas_errores = array();
        foreach($json->respuestas as $respuesta_json){
            $intento_resp = new diag\cc\IntentoRespuesta();
            $intento_resp->id_respuesta = $respuesta_json->id_respuesta;
            $intento_resp->id_intento = $json->id_intento;
            if ($intento_resp->create() === false) {
                $respuestas_errores[] = $respuesta_json->id_respuesta;
            }else{
                $respuestas_grabadas[] = $respuesta_json->id_respuesta;
            }
            unset($intento_resp);
        }

        $response->setJsonContent(
            [
                'status'   => 'OK',
                'messages'              => 'Respuestas grabadas',
                'respuestas_grabadas'   => $respuestas_grabadas,
                'respuestas_errores'    => $respuestas_errores,
            ]
        );           
        
        return $response;            
        
    }

    public function listar_intentos(){
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
        
        $intentos = diag\cc\Intento::find(['id_empresa = ?0',
        'bind' => [ $json->id_empresa ],
        'order' => 'id_intento DESC',]);        
        
        $response->setJsonContent(
            [
                'status'     => 'OK',
                'messages'   => 'Intentos realizados con la empresa '.$json->id_empresa,
                'Intentos'   => $intentos,
            ]
        );           
        
        return $response;              
    }

    public function obt_respuestas_intento(){
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
         
         $intento = diag\cc\Intento::findFirst(['id_empresa = ?0',
         'bind' => [ $json->id_intento ],]);
         if (!isset($json->id_intento) || $intento === false){
            // Cambiar el HTTP status
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'Intento no existe '.$json->id_intento,
                ]
            );
            return $response;
        }

        $respuestas_int = diag\cc\IntentoRespuesta::find(['id_intento = ?0',
        'bind' => [ $intento->id_intento ],]); 

        $respuestas = array();        
        foreach($respuestas_int as $int_res){
            $respuestas[] = diag\cc\OpcRespuesta::findFirst($int_res->id_respuesta);
        }

        $response->setJsonContent(
            [
                'status'     => 'OK',
                'messages'   => 'Respuestas del intento '.$intento->id_intento,
                'respuestas'   => $respuestas,
            ]
        );           
        
        return $response;     
    }

    public function puntear_categorias(){
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

         $intento = diag\cc\Intento::findfirst(['id_intento = ?0',
         'bind' => [ $json->id_intento ],]); 

         if ($intento === false){
            // Cambiar el HTTP status
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'Intento no existe',
                ]
            );
            return $response;
        }
        //Arreglos
        $sumatoria      = array();
        $tot_cat        = array();
        $set_resultados = array();
        $respuestas     = array();
        $resultado      = array();
        $preguntas      = array();

         $int_repuestas = diag\cc\IntentoRespuesta::find(['id_intento = ?0',
         'bind' => [ $intento->id_intento ],]); 

         foreach($int_repuestas as $intr){
            $respuestas[] = diag\cc\OpcRespuesta::findfirst(['id_respuesta = ?0',
            'bind' => [ $intr->id_respuesta ],]);           
         }
         //Obtener preguntas contestadas bien en el intento
         foreach($respuestas as $respuesta){
             if(isset($sumatoria[$respuesta->id_pregunta])){
                $sumatoria[$respuesta->id_pregunta] += $respuesta->puntaje;
             }else{
                $sumatoria[$respuesta->id_pregunta] = $respuesta->puntaje;
             }
             if($sumatoria[$respuesta->id_pregunta] >= 100){
                $preguntas[] = diag\cc\Pregunta::findfirst(['id_pregunta = ?0',
                'bind' => [ $respuesta->id_pregunta ],]); 
             }
         }

         //Obtener total de preguntas por categoria
         $preguntas_tot = diag\cc\Pregunta::find(['id_diagnostico = ?0',
                        'bind' => [ $intento->id_diagnostico ],]); 
         foreach($preguntas_tot as $pr_tot){
             if(isset( $tot_cat[$pr_tot->id_categoria]) ){
                $tot_cat[$pr_tot->id_categoria] += 1 ;
             }else{
                $tot_cat[$pr_tot->id_categoria] = 1 ;
             }      
         }

         //Preguntas correctas sumar
         foreach($preguntas as $pregunta){
            if( isset($resultado[$pregunta->id_categoria]) ){
                $resultado[$pregunta->id_categoria] ++ ;
            }else{
                $resultado[$pregunta->id_categoria] = 1 ;
            }
        }
         //Armar arreglo final
         foreach($preguntas_tot as $pregunta_tot){
            $categoria = diag\cc\Categoria::findfirst($pregunta_tot->id_categoria);
            if( !isset($resultado[$pregunta_tot->id_categoria]) ){
                $resultado[$pregunta_tot->id_categoria] = 0;
            }
            $set_resultados[$categoria->titulo] = ['total_bien'      => $resultado[$pregunta_tot->id_categoria],
                                                   'total_preguntas' => $tot_cat[$pregunta_tot->id_categoria],];
         }

         $response->setJsonContent(
            [
                'status'     => 'OK',
                'messages'   => 'Puntajes por categoria',
                'categoria_puntaje'  => $set_resultados,
                'int_respuesta' => $int_repuestas,
                'respuestas'   => $respuestas,
                'suma'        => $sumatoria,
                'preguntas_tot'  => $preguntas_tot,
                'preguntas'      => $preguntas,
                'tot_cat'     => $tot_cat,
                'categoria'   => $categoria,
                'result'      => $resultado,
                'resultado'   => $intento->resultado,
            ]
        );           
        
        return $response;            
        
    }

    public function listar_excel(){
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

        // $excel = new PHPExcel(); 
        // //Usamos el worsheet por defecto 
        // $sheet = $excel->getActiveSheet(); 
        // //creamos nuestro array con los estilos para titulos 
        // $h1 = array(
        // 'font' => array(
        //     'bold' => true, 
        //     'size' => 8, 
        //     'name' => 'Tahoma'
        // ), 
        // 'borders' => array(
        //     'allborders' => array(
        //     'style' => 'thin'
        //     )
        // ), 
        // 'alignment' => array(
        //     'vertical' => 'center', 
        //     'horizontal' => 'center'
        // )
        // ); 
        // //Agregamos texto en las celdas 

        // $sheet->setCellValue('A1', 'Prueba'); 
        // $sheet->setCellValue('B1', 'MatrixDevelopments'); 
        // //Damos formato o estilo a nuestras celdas 
        // $sheet->getStyle('A1:B1')->applyFromArray($h1); 
        // //exportamos nuestro documento 
        // $writer = new PHPExcel_Writer_Excel2007($excel); 
        // $writer->save('prueba1.xls');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setTitle('test worksheet');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Rezultati pretrage')
            ->setCellValue('A2', "Ime")
            ->setCellValue('C2', "Prezime")
            ->setCellValue('F2', "Adresa stanovanja");
    
        // file name to output
        $fname = date("Ymd_his") . ".xlsx";
    
        // temp file name to save before output
        $temp_file = tempnam(sys_get_temp_dir(), 'phpexcel');
    
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save($temp_file);

        // Redirect output to a client’s web browser (Excel2007)
        $response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->setHeader('Content-Disposition', 'attachment;filename="' . $fname . '"');
        $response->setHeader('Cache-Control', 'max-age=0');

        // If you're serving to IE 9, then the following may be needed
        $response->setHeader('Cache-Control', 'max-age=1');

        //Set the content of the response
        $response->setContent(file_get_contents($temp_file));

        // delete temp file
        unlink($temp_file);

        //Return the response
        return $response;
    }

}

