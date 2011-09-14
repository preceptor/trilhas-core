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
class Install_Form_Installation extends Zend_Form
{
    /**
     * (non-PHPdoc)
     * @see Zend_Form#init()
     */
    public function init()
    {
        $this->setAction('install.php')
             ->setMethod('post')
             ->setAttrib('enctype', 'multipart/form-data');

        $db = new Zend_Form_Element_Text('db');
        $db->setLabel('Database name')
           ->setAttrib('size', '55')
           ->setRequired();

        $user = new Zend_Form_Element_Text('user');
        $user->setLabel('Database username')
             ->setAttrib('size', '55')
             ->setRequired();

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Database password')
                 ->setRequired();
 
        $host = new Zend_Form_Element_Text('host');
        $host->setLabel('Database host')
             ->setAttrib('size', '55')
             ->setRequired();
        
        $login = new Zend_Form_Element_Text('login');
        $login->setLabel('Administration email')
              ->setAttrib('size', '55')
              ->setRequired();
        
        $user_password = new Zend_Form_Element_Password('user_password');
        $user_password->setLabel('Administration password')
                      ->setRequired();
                                   
        $course = new Zend_Form_Element_Checkbox('course');
        $course->setLabel('Create a demonstration data?');

        $this->addElement($db)
             ->addElement($user)
             ->addElement($password)
             ->addElement($host)
             ->addElement($login)
             ->addElement($user_password)
             ->addElement($course);

        
        $this->addElement('submit', 'Save');
    }
}