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
class ClassroomController extends Tri_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->view->title = "Classroom";
    }

    public function viewAction()
    {
        $id = Zend_Filter::filterStatic($this->_getParam('id'), 'int');
        
        Tri_Security::classroom($id, Zend_Auth::getInstance()->getIdentity()->id);
        
        $classroom = new Zend_Db_Table('classroom');
        $rowset    = $classroom->find($id);

        if (!count($rowset)) {
            $this->_redirect('/dashboard');
        }

        $row     = $rowset->current();
        $session = new Zend_Session_Namespace('data');
        $session->classroom_id = $row->id;
        $session->course_id = $row->course_id;

        $this->_helper->layout->setLayout('layout');
    }

    public function selectAction()
    {
        if ($this->_hasParam('id')) {
            $id = Zend_filter::filterStatic($this->_getParam('id'), 'int');
            $table  = new Zend_Db_Table('classroom');
            $course = new Tri_Db_Table('course');

            $where = array('course_id = ?' => $id,
                           'status = ?' => 'open',
                           'visibility = ?' => 'public',
                           'end >= ? OR end IS NULL' => date('Y-m-d'));

            $this->view->data   = $table->fetchAll($where);
            $this->view->course = $course->find($id)->current();
        }
    }

    public function signAction()
    {
        if ($this->_hasParam('id')) {
            $data = array();
            $id   = Zend_filter::filterStatic($this->_getParam('id'), 'int');

            if (Application_Model_Classroom::isAvailable($id)) {
                $table = new Zend_Db_Table('classroom');
                $row   = $table->fetchRow(array('id = ?' => $id));

                if ($row->register_type == 'payment') {
                    $this->_redirect('/payment/index/sign/id' . $id);
                } elseif ($row->register_type == 'process') {
                    $this->_redirect('/selection-process/index/sign/id' . $id);
                } elseif ($row->register_type == 'open') {
                    $data['user_id'] = Zend_Auth::getInstance()->getIdentity()->id;
                    $data['classroom_id'] = $id;

                    try {
                        $classroomUser = new Zend_Db_Table('classroom_user');
                        $classroomUser->createRow($data)->save();
                        $this->_helper->_flashMessenger->addMessage('Success');
                    } catch (Exception $e) {
                        $this->_helper->_flashMessenger->addMessage('Student already registered in this class');
                    }
                    $this->_redirect('dashboard');
                }
            }
        }
        $this->view->messages = array('Unavailable');
    }
}