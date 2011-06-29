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
class Admin_AclController extends Tri_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->_helper->layout->setLayout('admin');
//        $this->_helper->layout->disableLayout();
        $this->view->title = 'Access Control List';
    }
    
    public function indexAction()
    {
        $resources = Tri_Config::get('tri_resources', true);
        $this->view->data = $resources;
    }

    public function saveAction()
    {
        Tri_Config::set('tri_resources', $_POST['data'], true);
        
        $this->_helper->flashMessenger->addMessage('Success');
        
        $this->_redirect('admin/acl');
    }

    public function addAction()
    {
        $url  = explode('/', trim($this->_getParam('url')));
        $role = trim($this->_getParam('role'));

        if (count($url) != 3 || !$role) {
            $this->_helper->flashMessenger->addMessage('Error');
            $this->_redirect('admin/acl');
        }

        $resources = Tri_Config::get('tri_resources', true);

        $resources[$url[0]][$url[1]][$url[2]] = $role;

        Tri_Config::set('tri_resources', $resources, true);
        
        $this->_helper->flashMessenger->addMessage('Success');
        $this->_redirect('admin/acl');
    }
}
