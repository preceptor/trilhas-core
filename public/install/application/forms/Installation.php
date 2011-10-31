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
 * @package    Application_Form
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Application_Form_Installation extends Zend_Form
{
    /**
     * (non-PHPdoc)
     * @see Zend_Form#init()
     */
    public function init()
    {
        $this->addElementPrefixPath('Tri_Filter', 'Tri/Filter', 'FILTER');
        $this->addElementPrefixPath('Tri_Validate', 'Tri/Validate', 'VALIDATE');

        $this->setAction('index/install')
             ->setMethod('post');

        $db = new Zend_Form_Element_Text('db');
        $db->setLabel('Database name')
           ->setRequired();

        $user = new Zend_Form_Element_Text('user');
        $user->setLabel('Database username')
             ->setRequired();

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Database password')
                 ->setRequired();
 
        $host = new Zend_Form_Element_Text('host');
        $host->setLabel('Database host')
             ->setRequired();
        
        $login = new Zend_Form_Element_Text('login');
        $login->setLabel('Administration email')
              ->addValidator('EmailAddress')
              ->setRequired();
        
        $userPassword = new Zend_Form_Element_Password('user_password');
        $userPassword->setLabel('Administration password')
                     ->addValidator('Identical', false, array('token' => 'password_confirm'))
                     ->setRequired();
        
        $confirmPassword = new Zend_Form_Element_Password('password_confirm');
        $confirmPassword->setLabel('Administration password confirm')
                        ->setRequired();
                                   
        $course = new Zend_Form_Element_Checkbox('course');
        $course->setLabel('Create a demonstration data?');

        $this->addElement($host)
             ->addElement($user)
             ->addElement($password)
             ->addElement($db)
             ->addElement($login)
             ->addElement($userPassword)
             ->addElement($confirmPassword)
             ->addElement($course);
        
        $this->addElement('submit', 'Save', array('class' => 'btn primary'));
    }
}