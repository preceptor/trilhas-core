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
class Content_IndexController extends Tri_Controller_Action
{
    public function viewAction()
    {
        $id      = Zend_Filter::filterStatic($this->_getParam('id'), 'int');
        $string  = $this->_getParam('string');
        $session = new Zend_Session_Namespace('data');

        $restriction = Content_Model_Restriction::verify($id);
		
        if(isset($restriction['has']) && $restriction['has']) {
            $this->view->restriction = str_replace('%value%', $restriction['value'], $this->view->translate($restriction['content']));
        } else {
            $table = new Tri_Db_Table('content');
            $contentAccess = new Tri_Db_Table('content_access');
            $this->view->data = $table->find($id)->current();

            $data['content_id'] = $id;
            $data['user_id'] = Zend_Auth::getInstance()->getIdentity()->id;
            $data['classroom_id'] = $session->classroom_id;

            $contentAccess->createRow($data)->save();
        }
    }

    public function widgetAction()
    {
        $session = new Zend_Session_Namespace('data');
        $data = Application_Model_Content::fetchAllOrganize($session->course_id);
        $table = new Zend_Db_Table('course');
        
        if (!$data) {
            Application_Model_Content::createInitialContent($session->course_id);
            $data = Application_Model_Content::fetchAllOrganize($session->course_id);
        }

        $this->view->course = $table->find($session->course_id)->current()->name;
        $this->view->current = Application_Model_Content::getLastAccess($session->classroom_id, $data);
        $this->view->data = Zend_Json::encode($data);

        $session->contents = $this->view->data;
    }
}