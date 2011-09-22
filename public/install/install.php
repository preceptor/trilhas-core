<?php
ini_set('display_errors', 0);
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));


set_include_path(APPLICATION_PATH . '/../library');

require_once 'Zend/Loader/Autoloader.php';
require_once 'Tri/Installation.php';

$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('Tri');

$defaultApplicationFile = APPLICATION_PATH . '/configs/application.ini.default';
$applicationFile = APPLICATION_PATH . '/configs/application.ini';

$messages = array();

require_once 'forms/Installation.php';

$form = new Install_Form_Installation();

if ($form->isValid($_POST)) {
    $data = $form->getValues();
    
    $connect = mysql_connect($data['host'], $data['user'], $data['password']);
    if ($connect) {
        $sql = 'CREATE DATABASE IF NOT EXISTS ' . $data['db'];

        mysql_query($sql);
        mysql_select_db($data['db']);

        $config = new Zend_Config_Ini($defaultApplicationFile, null, true);
        $config->setExtend('staging', 'production');
        $config->setExtend('development', 'production');

        $config->development = array('phpSettings' => array('display_errors' => 1));
        $config->staging = array();

        $config->production->resources->db->params->username = $data['user'];
        $config->production->resources->db->params->dbname = $data['db'];
        $config->production->resources->db->params->password = $data['password'];
        $config->production->resources->db->params->host = $data['host'];
		
        try {
        	$writer = new Zend_Config_Writer_Ini();
        	$writer->write($applicationFile, $config);
        } catch (Exception $e) {
        	$messages[] = "Não foi possível escrever os arquivos de configuração";
        }

        $db = Zend_Db::factory('PDO_MYSQL',$config->production->resources->db->params);

        Zend_Db_Table::setDefaultAdapter($db);

        $installation = new Tri_Installation(dirname(__FILE__).'/');
        $installation->install();
        $installation->activate();

        $password = MD5("trilhas".$data['user_password']);

        $user = new Zend_Db_Table('user');
        $data = array('name'        => 'administrador',
                      'email'       => $data['login'],
                      'password'    => $password,
                      'role'       =>  'institution');

        $row = $user->createRow($data);
        $uId = $row->save();
        if ($_POST['course'] == 1) {
            $course = new Zend_Db_Table('course');
            $data = array('user_id'     => $uId,
                          'responsible' => $uId,
                          'name'        => 'Demo',
                          'description' => 'Demo course',
                          'status'      => 'active');

            $row = $course->createRow($data);
            $cId = $row->save();

            $classroom = new Zend_Db_Table('classroom');
            $data = array('course_id'   => $cId,
                          'responsible' => $uId,
                          'name'        => 'Demo',
                          'begin'       => date('Y-m-d'));

            $row = $classroom->createRow($data);
            $row->save();
        }

        $cache = Tri_Config::get('tri_cachemanager', true);
        $cache['backend']['options']['cache_dir'] = APPLICATION_PATH . '/../data/cache';
        Tri_Config::set('tri_cachemanager', $cache, true, 1);

        $translate = Tri_Config::get('tri_translate', true);
        $translate['data'] = APPLICATION_PATH . '/../data/language';
        Tri_Config::set('tri_translate', $translate, true, 1);

        $upload = Tri_Config::get('tri_upload_dir', true);
        $upload = APPLICATION_PATH . '/../public/upload';
        Tri_Config::set('tri_upload_dir', $upload, false, 0);

        header("Location: '/../../");
        exit;
    }
} 

$view = new Zend_View();
$view->addBasePath(realpath(dirname(__FILE__) . '/views/'));

$layout = new Zend_Layout();
$layout->setView($view);

$form->setView($view);
$form->populate($_POST);

$view->form = $form;

$layout->content = $view->render('form.phtml');

$messages[]  = 'Erro ao contectar com o servidor, confira os dados preenchidos e tente novamente';
$layout->messages = $messages;

echo $layout->render();
