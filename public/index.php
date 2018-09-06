<?php
use Phalcon\Di\FactoryDefault;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

       
try {

    // Use Loader() to autoload our model
   $loader = new Loader();
   
   $loader->registerNamespaces(
       [
           'diag\cc' => APP_PATH . '/models/',
       ]
   );
   
   $loader->registerDirs(
       [
           APP_PATH . '/controllers/',
           APP_PATH . '/models/',
       ]
   );
   
   $loader->register();
    
   $di = new FactoryDefault();
     
   // * Read services    
   include APP_PATH . '/config/services.php';
   //*/
   
   $app = new Micro($di);
   
// Crear un gestor de eventos
   // $eventsManager = new EventsManager();

   // $eventsManager->attach(
   //     'micro:beforeExecuteRoute',
   //     function (Event $event, $app) {
   //         if ($app->session->get('auth') === false) {
   //             $app->flashSession->error("El usuario no esta autorizado");
   //             $response = new Response();
   //             // $app->response->redirect('/');
   //             // $app->response->sendHeaders();
   //             $response->setJsonContent(
   //                 [
   //                     'status'   => 'ERROR',
   //                     'messages' => 'El usuario no esta autorizado',
   //                 ]
   //             );
   //             // Devolver (false) y detener la operación
   //             // return false;
   //         }
   //     }
   // );

   // // Enlazar el gestor de eventos a la aplicación
   // $app->setEventsManager($eventsManager);    

   // * Configurar rutas
    
   include APP_PATH . '/config/router.php';
   
   /**
    * Get config service for use in inline setup below
    
   $config = $di->getConfig();
*/
   /**
    * Include Autoloader
    
   include APP_PATH . '/config/loader.php';
   */
   /**
    * Handle the request
    */
   //$application = new \Phalcon\Mvc\Application($di);

   //echo str_replace(["\n","\r","\t"], '', $application->handle()->getContent());
   
// Recuperar todos los usuarios
$app->get(
   '/api/usuarios',
   function () use ($app) {
       $phql = 'SELECT * FROM syl\usuario\Usuario ORDER BY nombre';

       $usuarios = $app->modelsManager->executeQuery($phql);

       $data = [];

       foreach ($usuarios as $usuario) {
           $data[] = [
               'id_usuario'   => $usuario->id_usuario,
               'nombre'       => $usuario->nombre,
           ];
       }

       echo json_encode($data);
   }
);

// Searches for robots with $name in their name
$app->get(
   '/api/robots/search/{name}',
   function ($name) use ($app) {
       $phql = 'SELECT * FROM machine\robot\Robots WHERE name LIKE :name: ORDER BY name';

       $robots = $app->modelsManager->executeQuery(
           $phql,
           [
               'name' => '%' . $name . '%'
           ]
       );

       $data = [];

       foreach ($robots as $robot) {
           $data[] = [
               'id_robot'   => $robot->id_robot,
               'name' => $robot->name,
           ];
       }

       echo json_encode($data);
   }
);

// Retrieves robots based on primary key
$app->get(
    '/api/robots/{id:[0-9]+}',
   function ($id) use ($app) {
       $phql = 'SELECT * FROM machine\robot\Robots WHERE id_robot = :id_robot:';

       $robot = $app->modelsManager->executeQuery(
           $phql,
           [
               'id_robot' => $id,
           ]
       )->getFirst();

       // Create a response
       $response = new Response();

       if ($robot === false) {
           $response->setJsonContent(
               [
                   'status' => 'NOT-FOUND'
               ]
           );
       } else {
           $response->setJsonContent(
               [
                   'status' => 'FOUND',
                   'data'   => [
                       'id_robot'   => $robot->id_robot,
                       'name' => $robot->name
                   ]
               ]
           );
       }

       return $response;
   }
);


   
$app->handle();
   

} catch (\Exception $e) {
   echo $e->getMessage() . '<br>';
   echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
