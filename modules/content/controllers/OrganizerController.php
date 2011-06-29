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
class Content_OrganizerController extends Tri_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->view->title = "Organize";
    }

	public function indexAction()
	{
		$id		 = Zend_Filter::filterStatic($this->_getParam('id'), 'int');
        $session = new Zend_Session_Namespace('data');
		$content = new Tri_Db_Table('content');

        if ($id) {
			$this->view->id = $id;

			$where = $content->select()
							 ->from('content', array('id', 'title', 'content_id'))
							 ->where('course_id = ?', $session->course_id)
							 ->where('content_id = ?', $id)
							 ->order(array('position', 'id'));

			$this->view->data = $content->fetchAll($where)->toArray();
		} else {
			$where = $content->select()
							 ->from('content', array('id', 'title', 'content_id'))
							 ->where('course_id = ?', $session->course_id)
							 ->where('content_id IS NULL')
							 ->order(array('position', 'id'));

			$this->view->data = $content->fetchAll($where)->toArray();
		}

        $this->view->save = Zend_Filter::filterStatic($this->_getParam('save'), 'int');

        if (!$this->_hasParam('layout')) {
            $this->_helper->layout->disableLayout();
        }
	}
	
	public function saveAction()
	{
        $data    = $_POST['position'];
        $id      = Zend_Filter::filterStatic($this->_getParam('id'), 'int');
		$content = new Tri_Db_Table('content');
		
		$i = 1;
        foreach ($data as $key => $val) {
			$row = $content->find($key)->current();
            $row->position = $i;
            $row->save();

            $i++;
		}
		
		$this->_helper->_flashMessenger->addMessage('Success');
		$this->_redirect('/content/organizer/index/id/' . $id);
	}
}
