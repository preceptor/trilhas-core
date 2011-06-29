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
class DashboardController extends Tri_Controller_Action
{
    public function indexAction()
    {
        $identity  = Zend_Auth::getInstance()->getIdentity();
        $this->view->user = $identity;
        
        $this->_helper->layout->setLayout('layout');
    }
	
    public function moreAction()
    {
        $page = Zend_Filter::filterStatic($this->_getParam('page'), 'int');
        $identity = Zend_Auth::getInstance()->getIdentity();
        $courses  = Application_Model_Classroom::getAllByUser($identity->id);

        $this->view->timeline = Application_Model_Timeline::getByClassroom($courses, $page);
    }

    public function timelineAction()
    {
        $identity  = Zend_Auth::getInstance()->getIdentity();
        $courses   = Application_Model_Classroom::getAllByUser($identity->id);

        $this->view->timeline = Application_Model_Timeline::getByClassroom($courses, 1);
    }
}
