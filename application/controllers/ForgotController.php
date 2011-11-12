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
class ForgotController extends Tri_Controller_Action
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
        $this->view->title = "Forgot";
    }
	
	/**
	 * Action index.
	 *
	 * @return void
	 */
    public function indexAction()
    {
        $form = new Application_Form_Forgot();
        $this->view->form = $form;
    }

	/**
	 * Action send.
	 *
	 * @return void
	 */
    public function sendAction()
    {
		$form = new Application_Form_Forgot();
		$tableUser = new Tri_Db_Table('user');
    	$data = $this->_getAllParams();
	 	if ($form->isValid($data)) {
			$email = $this->_getParam('email');
			$user = $tableUser->fetchRow(array('email = ?' => $email));
			if (!$user->id) {
				$this->_helper->_flashMessenger->addMessage('user not avaliable');
				$this->_redirect('forgot/');
			}
			$this->view->name = $user->name;
			$this->view->url = $this->encryptUrl($user);
			$mail = new Zend_Mail(Tri_Config::get('tri_app_charset'));
			$mail->setBodyHtml($this->view->render('forgot/mail.phtml'));
	        $mail->setSubject($this->view->translate('Password recovery'));
	        $mail->addTo($user->email, $user->name);
			$mail->send();
            $this->_helper->_flashMessenger->addMessage('Success');
            $this->_redirect('forgot/');
		}
		$this->_helper->_flashMessenger->addMessage('Error');
        $this->_redirect('forgot/');
    }
	
	/**
	 * Action recovery.
	 *
	 * @return void
	 */
    public function saveAction()
    {
		$tableUser = new Tri_Db_Table('user');
		$form = new Application_Form_Recovery();
		$data = $this->_getAllParams();
		
		if ($form->isValid($data)) {
			$data = $form->getValues();
			$row = $tableUser->find($data['id'])->current();
            $row->setFromArray($data);
            $id = $row->save();
			
			if (!empty($id)) {
				$this->_helper->_flashMessenger->addMessage('Success');
			} else {
				$this->_helper->_flashMessenger->addMessage('Error');
			}
			$this->_redirect('/');
		}
		
		$messages = $form->getMessages();
		if (!empty($messages['password']['notMatch'])) {
			$this->_helper->_flashMessenger->addMessage($messages['password']['notMatch']);
		} else {
			$this->_helper->_flashMessenger->addMessage('Error');
		}
		
		$this->_redirect('/');
    }
	
	/**
	 * Action recovery.
	 *
	 * @return void
	 */
    public function recoveryAction()
    {
		$key = urldecode(base64_decode($this->_getParam('key')));
		$result = $this->decryptUrl($key);
		
		if (empty($result['user']->id)) {
			$this->_helper->_flashMessenger->addMessage('user not avaliable');
			$this->_redirect('/');
		}
		
		if (date("d/m/Y") != $result['date']) {
			$this->_helper->_flashMessenger->addMessage('key unsuccessful');
			$this->_redirect('/');
		}
		
		$form = new Application_Form_Recovery();
		$form->populate($result['user']->toArray());
		$this->view->form = $form;
    }
	
	/**
	 * Encrypt url for password recovery
	 *
	 * @param Object $user
	 * @return String
	 **/
	private function encryptUrl($user)
	{
		if (!is_object($user)) {
			throw new Exception('User is not object');
		}
		$encrypt = new Zend_Filter_Encrypt();
		$encrypt->setVector(Tri_Config::get('mail_vector'));
		$hash = $encrypt->filter("id/{$user->id}/date/" . date('Y-m-d'));
		return $this->view->serverUrl(). $this->view->baseUrl() . "/forgot/recovery?key=" . urlencode(base64_encode($hash));
	}
	
	/**
	 * Decrypt url for password recovery
	 *
	 * @param String $urlEncrypt
	 * @return array
	 **/
	private function decryptUrl($urlEncrypt)
	{
		if (empty($urlEncrypt)) {
			return false;
		}
		
		$tableUser = new Zend_Db_Table('user');
		
		$decrypt = new Zend_Filter_Decrypt();
		$decrypt->setVector(Tri_Config::get('mail_vector'));
		$decrypted = $decrypt->filter($urlEncrypt);
		$array = explode('/', $decrypted);
		$user = $tableUser->fetchRow(array('id =?' => $array[1]));
		$date = date("d/m/Y" ,strtotime($array[3]));
		
		$data['user'] = $user;
		$data['date'] = $date;
		return $data;
	}
}
