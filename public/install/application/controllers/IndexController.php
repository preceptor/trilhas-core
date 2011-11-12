<?php
/**
 * Trilhas - Learning Management System
 * Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * @category   Application
 * @package    Application_Controller
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class IndexController extends Tri_Controller_Action
{
    public function indexAction()
    {
        $dirs = array('configIsWritable' => APPLICATION_PATH . '/configs/',
                      'dataIsWritable' => APPLICATION_PATH . '/../data/',
                      'pluginsIsWritable' => APPLICATION_PATH . '/../plugins/',
                      'themesIsWritable' => APPLICATION_PATH . '/../themes/',
                      'uploadIsWritable' => APPLICATION_PATH . '/../public/upload/');
        
        $this->view->checked = true;
        
        foreach ($dirs as $var => $dir) {
            $this->view->$var  = 'YES';
            if (!is_writable($dir)) {
                $this->view->$var  = 'NO';
                $this->view->checked = false;
            }
        }
        
        $this->view->hasApplicationIni = file_exists(APPLICATION_PATH . '/configs/application.ini');
    }
    
    public function formAction()
    {
        $this->view->form = new Application_Form_Installation();
    }
    
    public function installAction()
    {
        $defaultApplicationFile = APPLICATION_PATH . '/configs/application.ini.default';
        $applicationFile        = APPLICATION_PATH . '/configs/application.ini';
        $messages               = array();

        $form = new Application_Form_Installation();

        if ($form->isValid($_POST)) {
            $data = $form->getValues();

            $connect = mysql_connect($data['host'], $data['user'], $data['password']);
            if ($connect) {
                //create db
                $sql = 'CREATE DATABASE IF NOT EXISTS ' . $data['db'];
                if (mysql_query($sql)) {

                    //create application.ini
                    $config = new Zend_Config_Ini($defaultApplicationFile, null, array('skipExtends' => true, 'allowModifications' => true));
                    $config->setExtend('staging', 'production');
                    $config->setExtend('development', 'production');

                    $config->development = array('phpSettings' => array('display_errors' => 1, 
                                                                        'error_reporting' => 'E_ALL'));
                    $config->staging = array();

                    $config->production->resources->db->params->username = $data['user'];
                    $config->production->resources->db->params->dbname = $data['db'];
                    $config->production->resources->db->params->password = $data['password'];
                    $config->production->resources->db->params->host = $data['host'];

                    try {
                        $writer = new Zend_Config_Writer_Ini();
                        $writer->write($applicationFile, $config);
                    } catch (Exception $e) {
                        $messages[] = "Error occurred while writing configuration file. Check permissions.";
                    }

                    //execute configuration.xml
                    $db = Zend_Db::factory('PDO_MYSQL',$config->production->resources->db->params);
                    Zend_Db_Table::setDefaultAdapter($db);

                    $installation = new Tri_Installation(APPLICATION_INSTALL_PATH . '/configs/');
                    $installation->install();
                    $installation->activate();

                    //create admin user
                    $password = MD5("trilhas".$data['user_password']);

                    $user = new Zend_Db_Table('user');
                    $data = array('name'        => 'administrador',
                                  'email'       => $data['login'],
                                  'password'    => $password,
                                  'role'       =>  'institution');

                    $row = $user->createRow($data);
                    $uId = $row->save();

                    //create demo data
                    if ($data['course'] == 1) {
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

                    //update paths
                    $cache = Tri_Config::get('tri_cachemanager', true);
                    $cache['backend']['options']['cache_dir'] = realpath(APPLICATION_PATH . '/../data/cache');
                    Tri_Config::set('tri_cachemanager', $cache, true, 1);

                    $translate = Tri_Config::get('tri_translate', true);
                    $translate['data'] = realpath(APPLICATION_PATH . '/../data/language');
                    Tri_Config::set('tri_translate', $translate, true, 1);

                    $upload = Tri_Config::get('tri_upload_dir', true);
                    $upload = realpath(APPLICATION_PATH . '/../public/upload');
                    Tri_Config::set('tri_upload_dir', $upload, false, 0);

                    header("Location: '/../../../");
                    exit;
                }
                
                $messages[] = "Error occurred while creating database. Check user permissions.";
            }
        } 

        $messages[]  = 'Connection error. Check provided information.';
        $this->view->messages = $messages;
        $this->view->form     = $form;
        $this->render('form');
    }
}
