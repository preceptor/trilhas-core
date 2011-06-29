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
 * @category   Admin
 * @package    Admin_Controller
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Admin_PluginController extends Tri_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->_helper->layout->setLayout('admin');
        $this->view->title = 'Plugin';
        $this->pluginsPath = APPLICATION_PATH . '/../plugins/';
    }
    
    public function indexAction()
    {
        $iterator = new DirectoryIterator($this->pluginsPath);
        $this->view->data = $iterator;
        $this->view->activated = Tri_Config::get('tri_plugins', true);
    }

    public function activateAction()
    {
        $name = $this->_getParam('name');
        $className = $this->_formatName($name) . '_Plugin';

        try {
            include($name.'/Plugin.php');
            Zend_Loader::loadClass($className);

            $plugin = new $className;
            $plugin->_activate();

            $this->_helper->flashMessenger->addMessage('Success');
        } catch(Exception $e) {
            echo $e->getMessage();exit;
            $this->_helper->flashMessenger->addMessage('Error');
        }

        $this->_redirect('admin/plugin');
    }

    public function desactivateAction()
    {
        $name = $this->_getParam('name');
        $className = $this->_formatName($name) . '_Plugin';

        try {
            include($name.'/Plugin.php');
            Zend_Loader::loadClass($className);
            
            $plugin = new $className;
            $plugin->_desactivate();
            
            $this->_helper->flashMessenger->addMessage('Success');
        } catch(Exception $e) {
            $this->_helper->flashMessenger->addMessage('Error');
        }
        
        $this->_redirect('admin/plugin');
    }

    public function _formatName($name)
    {
        $parts = explode('-', $name);
        foreach ($parts as $key => $part) {
            $parts[$key] = ucfirst($part);
        }
        return implode('', $parts);
    }
}
