<?php
//ini_set('display_errors', 1);
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));

set_include_path(APPLICATION_PATH . '/../library');

require_once 'Zend/Loader/Autoloader.php';

$autoloader = Zend_Loader_Autoloader::getInstance();

$view = new Zend_View();
$view->addBasePath(realpath(dirname(__FILE__) . '/views/'));

$layout = Zend_Layout::startMvc();

$layout->setView($view);
$layout->content = $view->render('index.phtml');

echo $layout->render();
?>