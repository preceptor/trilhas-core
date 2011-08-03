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
class Admin_SimulationController extends Tri_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->_helper->layout->setLayout('admin');
        $this->view->title = 'Simulation';
    }
    
    public function indexAction()
    {
        $configRoles = Tri_Config::get('tri_roles', true);
        $roles       = array();
        foreach ($configRoles as $role) {
            $roles[$role] = $this->view->translate($role);
        }
        $this->view->roles = $roles;
    }

    public function simulateAction()
    {
        $email        = $this->_getParam('email');
        $role         = $this->_getParam('role');
        $table        = new Tri_Db_Table('user');
        $columns      = array('id','name','email','role','image');
        $identity     = Zend_Auth::getInstance()->getIdentity();
        $returnObject = new stdClass();
        
        if ($email) {
            $row = $table->fetchRow(array('email = ?' => $email));

            if ($row) {
                $row = $row->toArray();
                foreach ($row as $name => $value) {
                    if (in_array($name,$columns)) {
                        $returnObject->{$name} = $value;
                    }
                }
                $returnObject->simulation = true;
                $returnObject->admin      = $identity;
                Zend_Auth::getInstance()->getStorage()->write($returnObject);
                
                $this->_redirect('index');
            }
        }
        
        if ($role) {
            $returnObject = clone $identity;
            $returnObject->simulation = true;
            $returnObject->admin      = $identity;
            $returnObject->role       = $role;
            Zend_Auth::getInstance()->getStorage()->write($returnObject);
            
            $this->_redirect('index');
        }
        
        $this->_redirect('admin/simulation/index');
    }
}