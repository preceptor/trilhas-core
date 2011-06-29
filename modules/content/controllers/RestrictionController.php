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
class Content_RestrictionController extends Tri_Controller_Action
{
    public function init() 
    {
        $this->view->title = "Restriction";
        parent::init();
    }
    
	public function indexAction()
	{
        $time    = new Tri_Db_Table('restriction_time'); 
        $panel   = new Tri_Db_Table('restriction_panel'); 
        $session = new Zend_Session_Namespace('data');
        
        $select = $time->select(true)
                       ->setIntegrityCheck(false)
                       ->join('content', 'content.id = restriction_time.content_id', array('title'))
                       ->where('classroom_id = ?', $session->classroom_id);
        $this->view->dataTime = $time->fetchAll($select);
        
        $select = $panel->select(true)
                        ->setIntegrityCheck(false)
                        ->join('content', 'content.id = restriction_panel.content_id', array('title'))
                        ->where('classroom_id = ?', $session->classroom_id);
        $this->view->dataPanel = $panel->fetchAll($select);
	}

    public function timeFormAction()
    {
		$id = Zend_Filter::filterStatic($this->_getParam('id'), 'int');
        $form = new Content_Form_Time();
        
        if ($id) {
            $table = new Tri_Db_Table('restriction_time');
            $row = $table->find($id)->current();
            
            $form->populate($row->toArray());
        }
        
        $this->view->form = $form;
	}
    
    public function timeSaveAction()
    {
        $form    = new Content_Form_Time();
        $table   = new Tri_Db_Table('restriction_time');
        $session = new Zend_Session_Namespace('data');
        $data    = $this->_getAllParams();

        if ($form->isValid($data)) {
            $data = $form->getValues();
            $data['classroom_id'] = $session->classroom_id;

            if (isset($data['id']) && $data['id']) {
                $row = $table->find($data['id'])->current();
                $row->setFromArray($data);
                $id = $row->save();
            } else {
                unset($data['id']);
                $row = $table->createRow($data);
                $id = $row->save();
            }

            $this->_helper->_flashMessenger->addMessage('Success');
            $this->_redirect('content/restriction/index/id/'.$id);
        }

        $this->view->messages = array('Error');
        $this->view->form = $form;
        $this->render('time-form');
    }
    
    public function timeDeleteAction()
    {
        $table = new Tri_Db_Table('restriction_time');
        $id    = Zend_Filter::filterStatic($this->_getParam('id'), 'int');

        if ($id) {
            $table->delete(array('id = ?' => $id));
            $this->_helper->_flashMessenger->addMessage('Success');
        }

        $this->_redirect('content/restriction/index/');
    }
    
    public function panelFormAction()
    {
		$id = Zend_Filter::filterStatic($this->_getParam('id'), 'int');
        $form = new Content_Form_Panel();
        
        if ($id) {
            $table = new Tri_Db_Table('restriction_panel');
            $row = $table->find($id)->current();
            
            $form->populate($row->toArray());
        }
        
        $this->view->form = $form;
	}
    
    public function panelSaveAction()
    {
        $form    = new Content_Form_Panel();
        $table   = new Tri_Db_Table('restriction_panel');
        $session = new Zend_Session_Namespace('data');
        $data    = $this->_getAllParams();

        if ($form->isValid($data)) {
            $data = $form->getValues();
            $data['classroom_id'] = $session->classroom_id;

            if (isset($data['id']) && $data['id']) {
                $row = $table->find($data['id'])->current();
                $row->setFromArray($data);
                $id = $row->save();
            } else {
                unset($data['id']);
                $row = $table->createRow($data);
                $id = $row->save();
            }

            $this->_helper->_flashMessenger->addMessage('Success');
            $this->_redirect('content/restriction/index/id/'.$id);
        }

        $this->view->messages = array('Error');
        $this->view->form = $form;
        $this->render('time-form');
    }
    
    public function panelDeleteAction()
    {
        $table = new Tri_Db_Table('restriction_panel');
        $id    = Zend_Filter::filterStatic($this->_getParam('id'), 'int');

        if ($id) {
            $table->delete(array('id = ?' => $id));
            $this->_helper->_flashMessenger->addMessage('Success');
        }

        $this->_redirect('content/restriction/index/');
    }
}