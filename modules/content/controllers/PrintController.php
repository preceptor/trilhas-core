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
 * @category   Content
 * @package    Content_Controller
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Content_PrintController extends Tri_Controller_Action 
{
    public function init()
    {
        parent::init();
        $this->view->title = "Print";
    }
    public function indexAction() 
    {
        $session = new Zend_Session_Namespace('data');
        $this->view->data = Application_Model_Content::fetchAllOrganize($session->course_id);
    }

    public function viewAction() 
    {
        $id      = Zend_Filter::filterStatic($this->_getParam('id'), 'int');
        $session = new Zend_Session_Namespace('data');
        
        $this->view->data = Content_Model_Print::fetchOrganizeWithContent($session->course_id, $id);
    }
}

