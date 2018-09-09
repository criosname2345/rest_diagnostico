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
$Visitas->post('/crear', 'crear_visita');
$app->mount($Visitas);

// Manejador Usuarios
// $Usuarios = new MicroCollection();
// $Usuarios->setHandler('UsuariosController', true);
// $Usuarios->setPrefix('/usuarios');
// $Usuarios->post('/listarsyl', 'listar_syllabus');
// // $Usuarios->get('/get/{id}', 'get');
// // $Usuarios->get('/add/{payload}', 'add');
// $app->mount($Usuarios);

// Manejador de syllabus - versiones - sesiones
// $Syllabus = new MicroCollection();
// $Syllabus->setHandler('SyllabusController', true);
// $Syllabus->setPrefix('/syllabus');
// $Syllabus->post('/crearsyl', 'crear_syllabus');
// $Syllabus->post('/creardet', 'crear_detalle');
// $Syllabus->post('/obtcompt', 'obtener_competenciast');
// $Syllabus->post('/crearcomp', 'crear_competencia');
// $Syllabus->post('/crearses', 'crear_sesion');
// $Syllabus->post('/consultar', 'consultar_syllabus');
// $Syllabus->post('/pdf', 'generar_pdf');
// // $products->get('/get/{id}', 'get');
// // $products->get('/add/{payload}', 'add');
// $app->mount($Syllabus);

