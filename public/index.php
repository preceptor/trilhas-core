<?php
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

set_include_path(APPLICATION_PATH . '/../library');

$config = APPLICATION_PATH . '/configs/application.ini';

if (!file_exists($config)) {
    header("Location: install/index.php");
    exit;
} 

require_once 'Zend/Application.php';
$application = new Zend_Application(APPLICATION_ENV, $config);
$application->bootstrap()->run();