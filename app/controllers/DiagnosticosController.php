<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use Phalcon\Security\Random;

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
            if (!$loger ){
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

        $respuestas = array();
        $respuestas = $this->obtener_preguntas_listar($json->id_diagnostico);
        if(!isset($json->id_diagnostico) || empty($respuestas) ){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'No existen preguntas para el id_diagnostico '.$id_diagnostico ,
                ]
                ); 
            return $response;
        }

        $response->setJsonContent(
            [
                'status'   => 'OK',
                'messages' => 'Preguntas diagnostico '.$id_diagnostico ,
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

        // $contacto = diag\cc\Contacto::findfirst(['id_contacto = ?0',
        // 'bind' => [ $us_ses['id_contacto'] ],]);

        $empresa_usuario = diag\cc\Empresa::findfirst(['id_empresa = ?0',
        'bind' => [ $json->id_empresa ],]);

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

        $intento = new diag\cc\Intento();
        $intento->fecha = date("Y-m-d");      
        $intento->resultado = $json->resultado;
        $intento->id_diagnostico = $CamCom->id_diagnostico;
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

    public function excel_emp_reg(){
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

        $rol = $this->obtener_rol() ;
        switch($rol){
            //Consultor Funcional
            case '1':
                $response->setStatusCode(409, 'Conflict');
                $response->setJsonContent(
                    [
                        'status'   => 'ERROR',
                        'messages' => 'Consultor funcional no puede descargar reporte excel',
                    ]
                );
                return $response;
                break;
            //Coordinador
            case '2':
                //Traer la empresa del usuario en sesion      
                $empresa_usuario = $this->obtener_empresa();
                //traer las empresas de la camara de comercio
                $empresas_cc = diag\cc\Empresa::find(['camara_comercio = ?0',
                'bind' => [ $empresa_usuario->camara_comercio ],]);                
                break;
            //Administrador
            case '3':
                if(isset($json->camara_comercio)){
                    //traer las empresas de la camara de comercio del json
                    $empresas_cc = diag\cc\Empresa::find(['camara_comercio = ?0',
                    'bind' => [ $json->camara_comercio ],]);
                    
                }else{
                    //Traer todas las empresas
                    $empresas_cc = diag\cc\Empresa::find();
                }
                break;
        }

        $excel = new PHPExcel(); 
        //Usamos el worsheet por defecto 
        $sheet = $excel->getActiveSheet(); 
        //Titulo del archivo
        $sheet->setTitle('Empresas registradas');
        //creamos nuestro array con los estilos para titulos 
        $h1 = array(
        'font' => array(
            'bold' => true, 
            'size' => 8, 
            'name' => 'Tahoma'
        ), 
        'borders' => array(
            'allborders' => array(
            'style' => 'thin'
            )
        ), 
        'alignment' => array(
            'vertical' => 'center', 
            'horizontal' => 'center'
        )
        ); 

        //Agregamos texto en las celdas - Titulos
        $sheet->setCellValue('A1', 'Razon Social'); 
        $sheet->setCellValue('B1', 'Nit'); 
        $sheet->setCellValue('C1', 'Afiliación'); 
        $sheet->setCellValue('D1', 'Página Web'); 
        $sheet->setCellValue('E1', 'Representante legal'); 
        $sheet->setCellValue('F1', 'Gerencia general'); 
        $sheet->setCellValue('G1', 'Dirección'); 
        $sheet->setCellValue('H1', 'Ciudad'); 
        $sheet->setCellValue('I1', 'Constitución'); 
        $sheet->setCellValue('J1', 'Ccit'); 
        $sheet->setCellValue('K1', 'Camara de comercio'); 
        $sheet->setCellValue('L1', 'Actividad economica'); 
        //Damos formato o estilo a nuestras celdas 
        $sheet->getStyle('A1:L1')->applyFromArray($h1); 

        //Contador de posiciones, comienza en la fila 2
        $pos_cont = 2;
        //Posiciones de empresas
        foreach($empresas_cc as $empresa_cc){

            $sheet->setCellValue('A'.$pos_cont , $empresa_cc->razon_social); 
            $sheet->setCellValue('B'.$pos_cont , $empresa_cc->nit); 
            $sheet->setCellValue('C'.$pos_cont , $empresa_cc->afiliacion); 
            $sheet->setCellValue('D'.$pos_cont , $empresa_cc->web); 
            $sheet->setCellValue('E'.$pos_cont , $empresa_cc->repr_legal); 
            $sheet->setCellValue('F'.$pos_cont , $empresa_cc->ger_general); 
            $sheet->setCellValue('G'.$pos_cont , $empresa_cc->direccion); 
            $sheet->setCellValue('H'.$pos_cont , $empresa_cc->ciudad); 
            $sheet->setCellValue('I'.$pos_cont , $empresa_cc->constitucion); 
            $sheet->setCellValue('J'.$pos_cont , $empresa_cc->ccit); 
            if($empresa_cc->es_cc === '1'){
                $sheet->setCellValue('K'.$pos_cont , 'Si'); 
            }else{
                $sheet->setCellValue('K'.$pos_cont , 'No'); 
            } 
            $sheet->setCellValue('L'.$pos_cont , $empresa_cc->actividad_economica); 
            $pos_cont ++;

        }

        //exportamos nuestro documento 
        try{
            $writer = new PHPExcel_Writer_Excel2007($excel); 
            $nombre_archivo = 'temp/Emp_'. date("Ymd_his") . ".xlsx";
            $writer->save($nombre_archivo);
        }catch(Exception $e){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'     => 'ERROR',
                    'messages'   => $e->getMessage(),
                ]
            );
            return $response;
        }
    
        // temp file name to save before output
        // $temp_file = tempnam(sys_get_temp_dir(), 'phpexcel');
    
        // Redirect output to a client’s web browser (Excel2007)
        $response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->setHeader('Content-Disposition', 'attachment;filename="' . $nombre_archivo . '"');
        $response->setHeader('Cache-Control', 'max-age=0');

        // If you're serving to IE 9, then the following may be needed
        $response->setHeader('Cache-Control', 'max-age=1');

        //Set the content of the response
        // $response->setContent(file_get_contents($temp_file));
        $response->setJsonContent(
            [
                'status'     => 'OK',
                'messages'   => 'Empresas registradas',
                'loc_archivo'   => $nombre_archivo,
                // 'empresas'   => $empresas_cc
            ]
        );  
        // delete temp file
        // unlink($temp_file);

        //Return the response
        return $response;
    }

    public function excel_vis_reg(){
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

        $visitas = array();
        $rol = $this->obtener_rol() ;
        switch($rol){
            //Consultor Funcional
            case '1':
                $response->setStatusCode(409, 'Conflict');
                $response->setJsonContent(
                    [
                        'status'   => 'ERROR',
                        'messages' => 'Consultor funcional no puede descargar reporte excel',
                    ]
                );
                return $response;
                break;
            //Coordinador
            case '2':
                //Traer las visitas del usuario en sesion      
                $empresa_usuario = $this->obtener_empresa();
                //traer las visitas de la camara de comercio
                $empresas_cc = diag\cc\Empresa::find(['camara_comercio = ?0',
                'bind' => [ $empresa_usuario->camara_comercio ],]);     

                foreach($empresas_cc as $empresa_cc){
                    $visitas_emp = diag\cc\Visita::find(['id_empresa = ?0',
                    'bind' => [ $empresa_cc->id_empresa ],]);
                    foreach($visitas_emp as $vis_emp){
                        $visitas[] = $vis_emp;
                    }      
                }
                break;
            //Administrador
            case '3':
                if(isset($json->camara_comercio)){
                    //traer las empresas de la camara de comercio del json
                    $empresas_cc = diag\cc\Empresa::find(['camara_comercio = ?0',
                    'bind' => [ $json->camara_comercio ],]);     
    
                    foreach($empresas_cc as $empresa_cc){
                        $visitas_emp = diag\cc\Visita::find(['id_empresa = ?0',
                        'bind' => [ $empresa_cc->id_empresa ],]);   
                        foreach($visitas_emp as $vis_emp){
                            $visitas[] = $vis_emp;
                        }  
                    }
                    
                }else{
                    //Traer todas las visitas
                    $visitas = diag\cc\Visita::find();
                }
                break;
        }

        $salida_exc = array();

        foreach ($visitas as $visita){
            $usu_vis = diag\cc\Usuario::findfirst(['id_usuario = ?0',
                'bind' => [ $visita->id_usuario ],]);   
            $contacto = diag\cc\Contacto::findfirst(['id_contacto = ?0',
            'bind' => [ $usu_vis->id_contacto ],]);   
            $emp_visitada = diag\cc\Empresa::findfirst(['id_empresa = ?0',
            'bind' => [ $visita->id_empresa ],]);
            $cat_visitada = diag\cc\Categoria::findfirst(['id_categoria = ?0',
            'bind' => [ $visita->id_categoria ],]);
                
            $salida_exc[] = ['id_visita' => $visita->id_visita,
                             'fecha'     => $visita->fecha,
                             'comentario' => $visita->comentario,
                             'empresa' => $emp_visitada->razon_social,
                             'nombre_con' => $contacto->nombre.' '.$contacto->p_apellido,
                             'categoria' => $cat_visitada->titulo,
                            ]; 
        }
        //Si no hay visitas registradas informar
        if(empty($salida_exc)){
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'No existen visitas registradas para la consulta',
                ]
            );
    
            return $response;   
        }
        
        $excel = new PHPExcel(); 
        //Usamos el worsheet por defecto 
        $sheet = $excel->getActiveSheet(); 
        //Titulo del archivo
        $sheet->setTitle('Visitas registradas');
        //creamos nuestro array con los estilos para titulos 
        $h1 = array(
        'font' => array(
            'bold' => true, 
            'size' => 8, 
            'name' => 'Tahoma'
        ), 
        'borders' => array(
            'allborders' => array(
            'style' => 'thin'
            )
        ), 
        'alignment' => array(
            'vertical' => 'center', 
            'horizontal' => 'center'
        )
        ); 

        //Agregamos texto en las celdas - Titulos
        $sheet->setCellValue('A1', 'Fecha de la visita'); 
        $sheet->setCellValue('B1', 'Empresa visitada'); 
        $sheet->setCellValue('C1', 'Consultor visitante'); 
        $sheet->setCellValue('D1', 'Categoría'); 
        $sheet->setCellValue('E1', 'Observaciones de la visita'); 
        //Damos formato o estilo a nuestras celdas 
        $sheet->getStyle('A1:E1')->applyFromArray($h1); 

        //Contador de posiciones, comienza en la fila 2
        $pos_cont = 2;
        //Posiciones de visitas
        foreach($salida_exc as $sal_exc){

            $sheet->setCellValue('A'.$pos_cont , $sal_exc['fecha']); 
            $sheet->setCellValue('B'.$pos_cont , $sal_exc['empresa']); 
            $sheet->setCellValue('C'.$pos_cont , $sal_exc['nombre_con']); 
            $sheet->setCellValue('D'.$pos_cont , $sal_exc['categoria']); 
            $sheet->setCellValue('E'.$pos_cont , $sal_exc['comentario']); 
            $pos_cont ++;
        }

        //exportamos nuestro documento 
        $writer = new PHPExcel_Writer_Excel2007($excel); 
        $nombre_archivo = 'temp/Vis_'. date("Ymd_his") . ".xlsx";
        $writer->save($nombre_archivo);
    
        // temp file name to save before output
        $temp_file = tempnam(sys_get_temp_dir(), 'phpexcel');
    
        // Redirect output to a client’s web browser (Excel2007)
        $response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->setHeader('Content-Disposition', 'attachment;filename="' . $nombre_archivo . '"');
        $response->setHeader('Cache-Control', 'max-age=0');

        // If you're serving to IE 9, then the following may be needed
        $response->setHeader('Cache-Control', 'max-age=1');

        //Set the content of the response
        // $response->setContent(file_get_contents($temp_file));
        $response->setJsonContent(
            [
                'status'     => 'OK',
                'messages'   => 'Visitas registradas',
                'loc_archivo'   => $nombre_archivo,
            ]
        );  
        // delete temp file
        unlink($temp_file);

        //Return the response
        return $response;        

    }

    public function excel_diag_reg(){
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

        $intentos = array();
        $rol = $this->obtener_rol() ;
        switch($rol){
            //Consultor Funcional
            case '1':
                $response->setStatusCode(409, 'Conflict');
                $response->setJsonContent(
                    [
                        'status'   => 'ERROR',
                        'messages' => 'Consultor funcional no puede descargar reporte excel',
                    ]
                );
                return $response;
                break;
            //Coordinador
            case '2':
                //Traer los diagnosticos de la camara de comercio del usuario en sesion      
                $empresa_usuario = $this->obtener_empresa();
                //traer el diagnostico de la camara de comercio
                $diag_cc = diag\cc\Diagnostico::findfirst(['id_empresa = ?0',
                'bind' => [ $empresa_usuario->camara_comercio ],]); 
                //Traer todos los intentos del diagnostico
                $intentos = diag\cc\Intento::find(['id_diagnostico = ?0',
                'bind'  => [ $diag_cc->id_diagnostico ],
                'order' => 'id_empresa DESC',]);                

                break;
            //Administrador
            case '3':
                if(isset($json->camara_comercio)){
                    //traer el diagnostico de la camara de comercio del json
                    $diag_cc = diag\cc\Diagnostico::findfirst(['id_empresa = ?0',
                    'bind' => [ $json->camara_comercio ],]); 
                    //Traer todos los intentos del diagnostico
                    $intentos = diag\cc\Intento::find(['id_diagnostico = ?0',
                    'bind'  => [ $diag_cc->id_diagnostico ],
                    'order' => 'id_empresa DESC',]);   
                    
                }else{
                    //Traer todas las visitas
                    $intentos = diag\cc\Intento::find(['order' => 'id_empresa DESC',]);
                }
                break;
        }    

        $salida_exc = array();
        
        foreach($intentos as $intento){

            $emp_int = diag\cc\Empresa::findfirst(['id_empresa = ?0',
            'bind' => [ $intento->id_empresa ],]);

            $salida_exc[] = ['id_intento' => $intento->id_intento,
                            'fecha'       => $intento->fecha,
                            'resultado'   => $intento->resultado,
                            'empresa'     => $emp_int->razon_social,
                            ]; 

        }

        $excel = new PHPExcel(); 
        //Usamos el worsheet por defecto 
        $sheet = $excel->getActiveSheet(); 
        //Titulo del archivo
        $sheet->setTitle('Diagnosticos registrados');
        //creamos nuestro array con los estilos para titulos 
        $h1 = array(
        'font' => array(
            'bold' => true, 
            'size' => 8, 
            'name' => 'Tahoma'
        ), 
        'borders' => array(
            'allborders' => array(
            'style' => 'thin'
            )
        ), 
        'alignment' => array(
            'vertical' => 'center', 
            'horizontal' => 'center'
        )
        ); 

        //Agregamos texto en las celdas - Titulos
        $sheet->setCellValue('A1', 'Fecha del diagnostico'); 
        $sheet->setCellValue('B1', 'Empresa'); 
        $sheet->setCellValue('C1', 'Resultado obtenido'); 
        //Damos formato o estilo a nuestras celdas 
        $sheet->getStyle('A1:C1')->applyFromArray($h1); 

        //Contador de posiciones, comienza en la fila 2
        $pos_cont = 2;
        //Posiciones de visitas
        foreach($salida_exc as $sal_exc){

            $sheet->setCellValue('A'.$pos_cont , $sal_exc['fecha']); 
            $sheet->setCellValue('B'.$pos_cont , $sal_exc['empresa']); 
            $sheet->setCellValue('C'.$pos_cont , $sal_exc['resultado']); 
            $pos_cont ++;
        }

        //exportamos nuestro documento 
        $writer = new PHPExcel_Writer_Excel2007($excel); 
        $nombre_archivo = 'temp/Diag_'. date("Ymd_his") . ".xlsx";
        $writer->save($nombre_archivo);
    
        // temp file name to save before output
        // $temp_file = tempnam(sys_get_temp_dir(), 'phpexcel');
    
        // Redirect output to a client’s web browser (Excel2007)
        $response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->setHeader('Content-Disposition', 'attachment;filename="' . $nombre_archivo . '"');
        $response->setHeader('Cache-Control', 'max-age=0');

        // If you're serving to IE 9, then the following may be needed
        $response->setHeader('Cache-Control', 'max-age=1');
       
        $response->setJsonContent(
            [
                'status'     => 'OK',
                'messages'   => 'Diagnosticos registrados',
                'loc_archivo'   => $nombre_archivo,
                'salida'     => $salida_exc,
            ]
        );  

        //Return the response
        return $response;        
        
    }

    public function generar_link(){
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

        $random = new Random();
        $link = new diag\cc\Link();
        // Genera una cadena base64 de URL-segura con largo $len.
        $len = 25;
        $link->url = $random->base64Safe($len);
        $link->id_empresa = $json->id_empresa;
        $link->fecha          = date("Y-m-d");

        if ($link->create() === false) {
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'No se ha podido crear el link',
                    'Diagnostico'   => $json->id_diagnostico,
                ]
            );           
        }else{

            $response->setJsonContent(
                [
                    'status'   => 'OK',
                    'messages' => 'Se registro correctamente el link',
                    'Link'   => $link,
                ]
            );              
        }     
        
        return $response;  
    }

    public function val_link_lp(){
        // Crear una respuesta
        $response = new Response();
        if ($this->request->isPost()) {
            $json = $this->request->getJsonRawBody();
        }else{
                $response->setStatusCode(404, 'Not Found');
                return $response;
        } 

        if($json->url === null){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'Link no validado',
                ]
            ); 
            return $response;   
        }

        $link = diag\cc\Link::findfirst(['url = ?0',
        'bind' => [ $json->url ],]);    
        if($link === false){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'Url no registrada',
                    'url'      => $json->url,
                ]
            );             
            return $response;   
        }
        if($link->diligenciado === 1){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'Url ya fue diligenciada',
                    'url'      => $json->url,
                ]
            );             
            return $response; 
        }
        
        $fecha_link   = date_create($link->fecha);
        $fec_act      = date("Y-m-d");
        $fecha_actual = date_create($fec_act);
        $diff_dias = date_diff($fecha_link, $fecha_actual)->format('%a');
        //Validar fecha del link
        if($diff_dias > 0){
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'La url registrada ya expiro, por favor contactar con el consultor encargado',
                    'url'      => $json->url,
                ]
            );             
            return $response;   
        }
        //Consultar la camara de comercio de la empresa
        $emp_cons = diag\cc\Empresa::findfirst(['id_empresa = ?0',
        'bind' => [ $link->id_empresa ],]); 

        $diagnostico = diag\cc\Diagnostico::findfirst(['id_empresa = ?0',
        'bind' => [ $emp_cons->camara_comercio ],]); 

        //Link Valido
        $respuestas = array();
        $respuestas = $this->obtener_preguntas_listar($diagnostico->id_diagnostico);
        if(!isset($link) || empty($respuestas) ){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'No existen preguntas para el id_diagnostico '.$diagnostico->id_diagnostico ,
                ]
                ); 
            return $response;
        }

        $response->setJsonContent(
            [
                'status'   => 'OK',
                'messages' => 'Preguntas diagnostico '.$id_diagnostico ,
                'Preguntas' => $respuestas ,
            ]
            );         
                                              
        return $response;            

    }

    public function val_link_ri(){
        // Crear una respuesta
        $response = new Response();
        if ($this->request->isPost()) {
            $json = $this->request->getJsonRawBody();
        }else{
                $response->setStatusCode(404, 'Not Found');
                return $response;
        }
        
        if($json->url === null){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'Link no validado',
                ]
            ); 
            return $response;   
        }

        $link = diag\cc\Link::findfirst(['url = ?0',
        'bind' => [ $json->url ],]);    
        if($link === false){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'Url no registrada',
                    'url'      => $json->url,
                ]
            );             
            return $response;   
        }
        if($link->diligenciado != 0){
            $response->setStatusCode(409, 'Conflict');
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => 'Url ya fue diligenciada',
                    'url'      => $json->url,
                ]
            );             
            return $response; 
        }

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

        $empresa_usuario = diag\cc\Empresa::findfirst(['id_empresa = ?0',
        'bind' => [ $link->id_empresa ],]);

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

        $intento = new diag\cc\Intento();
        $intento->fecha = date("Y-m-d");      
        $intento->resultado = $json->resultado;
        $intento->id_diagnostico = $CamCom->id_diagnostico;
        $intento->id_empresa =  $link->id_empresa;

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
            $link->diligenciado = 1; //Registrado intento
            $link->update();        
        }     
        
        return $response;    
        
    }

}

