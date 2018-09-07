<?php

use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Direct as Flash;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * Setting up the view component
 */

/**
 * Database connection is created based in the parameters defined in the configuration file
 */

    $di->set(
        'db',
        function () {
            $config = $this->getConfig();
    //       return new PdoMysql(
            return new PdoMysql(
                [ 
                    'host'     => $config->database->host,
                    'username' => $config->database->username,
                    'password' => $config->database->password,
                    'dbname'   => $config->database->dbname,
                    'charset'  => 'utf8'
                ]
            );
        }
    );

$di->set('flash', function () {
    return new Flash([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);
});

/**
// Iniciar sesión por primera vez cuando algún componente solicite el servicio de session
 */
$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});
// Iniciar sesión por primera vez cuando algún componente solicite el servicio de session
    // $di->setShared(
    //     'session',
    //     function () {
    //         $session = new Session();

    //         $session->start();

    //         return $session;
    //     }
    // );