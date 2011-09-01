<?php

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));


set_include_path(APPLICATION_PATH . '/../library');

require_once 'Zend/Loader/Autoloader.php';
require_once 'Tri/Installation.php';

$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('Tri');

$defaultApplicationFile = APPLICATION_PATH . '/configs/application.ini.default';
$applicationFile = APPLICATION_PATH . '/configs/application.ini';

$conect = mysql_connect($_POST['host'], $_POST['user'], $_POST['password']);
if ($conect) {

    $sql = 'CREATE DATABASE IF NOT EXISTS ' . $_POST['db'];
    mysql_query($sql);
    mysql_select_db($_POST['db']);

    $config = new Zend_Config_Ini($defaultApplicationFile, null, true);
    $config->setExtend('staging', 'production');
    $config->setExtend('development', 'production');
    
    $config->development = array('phpSettings' => array('display_errors' => 1));
    $config->staging = array();
    
    $config->production->resources->db->params->username = $_POST['user'];
    $config->production->resources->db->params->dbname = $_POST['db'];
    $config->production->resources->db->params->password = $_POST['password'];
    $config->production->resources->db->params->host = $_POST['host'];

    $writer = new Zend_Config_Writer_Ini();
    $writer->write($applicationFile, $config);
    
    $db = Zend_Db::factory('PDO_MYSQL',array('host' => $_POST['host'], 
                                             'username' => $_POST['user'], 
                                             'password' => $_POST['password'], 
                                             'dbname' => $_POST['db'],
                                             'charset' => 'UTF8'));
    
    Zend_Db_Table::setDefaultAdapter($db);
    
    
    $installation = new Tri_Installation(dirname(__FILE__).'/');
    $installation->install();
    $installation->activate();
    
    $password = MD5("trilhas".$_POST['userpassword']);
    
    $user = new Zend_Db_Table('user');
    $data = array('name'        => 'administrador',
                  'email'       => $_POST['login'],
                  'password'    => $password,
                  'role'       =>  'institution');

    $row = $user->createRow($data);
    $uId = $row->save();

    if($_POST['course'] == 1){
        
        $course = new Zend_Db_Table('course');
        $data = array('user_id'     => $uId,
                      'responsible' => $uId,
                      'name'        => 'Demonstraçao',
                      'description' => 'Curso de demonstraçao',
                      'status'      => 'active');

        $row = $course->createRow($data);
        $cId = $row->save();
                
        $classroom = new Zend_Db_Table('classroom');
        $data = array('course_id'   => $cId,
                      'responsible' => $uId,
                      'name'        => 'Demonstraçao',
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
} else {
    echo 'Erro ao conectar com o servidor mysql';
}
?>