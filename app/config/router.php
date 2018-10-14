<?php

use Phalcon\Mvc\Micro\Collection as MicroCollection;

//Index - autenticacion y control de sesion - seguridad
$Inicio = new MicroCollection();
// Establece el manejador principal. Por ejemplo, la instancia de un controlador
$Inicio->setHandler('IndexController', true);
// Establece un prefijo común para todas la rutas
$Inicio->setPrefix('/api/index');
// Usa el método 'obtener' en IndexController
$Inicio->post('/aut', 'autenticar');

$app->mount($Inicio);

// Gestion de visitas
$Visitas = new MicroCollection();
$Visitas->setHandler('VisitasController', true);
$Visitas->setPrefix('/visitas');
$Visitas->post('/obt', 'obt_visita');
$Visitas->post('/listar', 'listar_visitas');
$Visitas->post('/crear', 'crear_visita');
$Visitas->post('/categorias', 'listar_categorias');
$app->mount($Visitas);

// Gestion de contactos
$Contactos = new MicroCollection();
$Contactos->setHandler('ContactosController', true);
$Contactos->setPrefix('/contactos');
$Contactos->post('/crear', 'crear_contacto');
$Contactos->post('/listar', 'listar_contactos');
$app->mount($Contactos);

// Manejador Empresas
$Empresas = new MicroCollection();
$Empresas->setHandler('EmpresasController', true);
$Empresas->setPrefix('/empresas');
$Empresas->post('/listar', 'listar_empresas');
$Empresas->post('/crear', 'crear_empresa');
// $Empresas->get('/get/{id}', 'get');
// $Empresas->get('/add/{payload}', 'add');
$app->mount($Empresas);

// Manejador de Diagnosticos
$Diagnostico = new MicroCollection();
$Diagnostico->setHandler('DiagnosticosController', true);
$Diagnostico->setPrefix('/diagnostico');
$Diagnostico->post('/crear', 'crear_diagnostico');
$Diagnostico->post('/pregunta', 'grabar_pregunta');
$Diagnostico->post('/listar_p', 'listar_preguntas');
$Diagnostico->post('/reg_intento', 'realizar_intento');
$Diagnostico->post('/grabar_respuestas', 'grabar_resp_intento');
$Diagnostico->post('/intentos', 'listar_intentos');
$Diagnostico->post('/resp_int', 'obt_respuestas_intento');
$Diagnostico->post('/p_cat', 'puntear_categorias');
$Diagnostico->post('/exc_emp_reg', 'excel_emp_reg');
$app->mount($Diagnostico);

