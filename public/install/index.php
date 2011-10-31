<?php
defined('APPLICATION_INSTALL_PATH')
    || define('APPLICATION_INSTALL_PATH', realpath(dirname(__FILE__) . '/application'));

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));

defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

set_include_path(APPLICATION_PATH . '//../library');

$config = APPLICATION_INSTALL_PATH . '/configs/application.ini';

require_once 'Zend/Application.php';

$application = new Zend_Application(APPLICATION_ENV, $config);
$application->bootstrap()
            ->run();