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
class Admin_CourseController extends Tri_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->_helper->layout->setLayout('admin');
        $this->view->title = "Courses";
    }
	
    public function indexAction()
    {
        $page  = Zend_Filter::filterStatic($this->_getParam('page'), 'int');
        $query = Zend_Filter::filterStatic($this->_getParam('query'), 'alnum');
        $table = new Zend_Db_Table('course');
        $select = $table->select(true)
                        ->setIntegrityCheck(false)
                        ->join('user', 'user.id = course.user_id', array())
                        ->order('status')
                        ->group('course.id');

        if ($query) {
            $select->where('name LIKE (?)', "%$query%");
        }

        $paginator = new Tri_Paginator($select, $page);
        $this->view->data = $paginator->getResult();
    }
	
	/**
	 * Action form
	 *
	 * @return void
	 */
    public function formAction()
    {
        $id   = Zend_Filter::filterStatic($this->_getParam('id'), 'int');
        $form = new Admin_Form_Course();

        if ($id) {
            Tri_Security::course($id, Zend_Auth::getInstance()->getIdentity()->id);
            $table = new Tri_Db_Table('course');
            $row   = $table->find($id)->current();

            if ($row) {
                $form->populate($row->toArray());
            }
        }

        $this->view->form = $form;
    }
	
	/**
	 * Action save
	 *
	 * @return void
	 */
    public function saveAction()
    {
        $form  = new Admin_Form_Course();
        $table = new Tri_Db_Table('course');
        $data  = $this->_getAllParams();

        if ($form->isValid($data)) {
            if (!$form->image->receive()) {
                $this->_helper->_flashMessenger->addMessage('Image fail');
            }

            $data = $form->getValues();
            if (!$form->image->getValue()) {
                unset($data['image']);
            }

            if (!$data['responsible']) {
                unset($data['responsible']);
            }

            $data['user_id'] = Zend_Auth::getInstance()->getIdentity()->id;

            if (isset($data['id']) && $data['id']) {
                $row = $table->find($data['id'])->current();
                $row->setFromArray($data);
                $id = $row->save();
            } else {
                unset($data['id']);
                $classroom = new Zend_Db_Table('classroom');
                $row = $table->createRow($data);
                $id = $row->save();

                $responsible = null;
                if (isset($data['responsible'])) {
                    $responsible = $data['responsible'];
                }

                $data = array('course_id'   => $id,
                              'responsible' => $responsible,
                              'name'        => 'Open ' . $data['name'],
                              'begin'       => date('Y-m-d'));

                $row = $classroom->createRow($data);
                $row->save();
            }

            $this->_helper->_flashMessenger->addMessage('Success');
            $this->_redirect('admin/course/index/id/'.$id);
        }

        $this->_helper->_flashMessenger->addMessage('Error');
        $this->view->form = $form;
        $this->render('form');
    }
}
