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
class Admin_UserController extends Tri_Controller_Action
{
	/**
	 * Init
 	 *
	 * Call parent init and set title box.
	 *
	 * @return void
	 */
    public function init()
    {
        parent::init();
        $this->view->title = "User";
        $this->_helper->layout->setLayout('admin');

    }
	
	/**
	 * Action index.
	 *
	 * @return void
	 */
    public function indexAction()
    {
        $page  = $this->_getParam('page');
        $query = $this->_getParam('query');
        $model = new Admin_Model_User();
        
        $this->view->data = $model->findByNameOrEmail($query, $page);
    }

    public function formAction()
    {
        $id   = Zend_Filter::filterStatic($this->_getParam('id'), 'int');
        $form = new Admin_Form_User();

        $model = new Admin_Model_User();
        $data  = $model->findById($id);
        $form->populate($data);
        
        $this->view->form = $form;
    }

    public function saveAction()
    {
        $form  = new Admin_Form_User();
        $model = new Admin_Model_User();
        $data  = $this->_getAllParams();

        if ($form->isValid($data)) {
            $data = $form->getValues();
            
            if (!$form->image->receive()) {
                $model->addMessage('Image fail');
            }
            
            if ($model->save($data)) {
                if (isset($data['id']) && !$data['id']) {
                    $data['password'] = $this->_getParam('password');

                    $this->view->data = $data;

                    $mail = new Zend_Mail(Tri_Config::get('tri_app_charset'));
                    $mail->setBodyHtml($this->view->render('user/welcome.phtml'));
                    $mail->setSubject($this->view->translate('Welcome'));
                    $mail->addTo($data['email'], $data['name']);
                    $mail->send();
                }
            
                $this->_helper->_flashMessenger->addMessage('Success');
                $this->_redirect('admin/user');
            }
        }
        
        $form->getElement('born')->removeFilter('Date');
        $form->populate($data);
        $this->view->messages = $model->getMessages();
        $this->view->form = $form;
        $this->render('form');
    }
}
