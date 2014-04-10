<?php

//echo '<pre>'; print_r($_SERVER); exit;

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));

defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

defined('DIR_CONFIG')
    || define('DIR_CONFIG', APPLICATION_PATH . '/configs');

defined('ADMINISTRACION_URL')
    || define('ADMINISTRACION_URL', 'http://twitter.com/daswort/');
    
defined('URL')
    || define('URL', 'http://' . $_SERVER['HTTP_HOST'] . '/personas-admin');

defined('URI')
    || define('URI', $_SERVER['REQUEST_URI']); 

defined('HASH_PASSWORD')
    || define('HASH_PASSWORD', 'JKNLKjhkBbjhgUytrurjb5571jHGd');

defined('UPLOAD_DIR')
    || define('UPLOAD_DIR', __DIR__ . "/../images/fotos/");

defined('UPLOAD_URL')
    || define('UPLOAD_URL', 'http://' . $_SERVER['SERVER_NAME'] . "/images/fotos/");

defined('NOMBRE_APP')
    || define('NOMBRE_APP', "Funcionarios // Portal Davila");

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();