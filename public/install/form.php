<?php
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));

set_include_path(APPLICATION_PATH . '/../library');

require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();

require_once 'forms/Installation.php';

$view = new Zend_View();
$view->addBasePath(realpath(dirname(__FILE__) . '/views/'));

$layout = Zend_Layout::startMvc();

$layout->setView($view);

$form = new Install_Form_Installation();
$form->setView($view);

$view->form = $form;
$layout->content = $view->render('form.phtml');

echo $layout->render();
?>