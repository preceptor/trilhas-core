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
class Application_Form_User extends Zend_Form
{
    /**
     * (non-PHPdoc)
     * @see Zend_Form#init()
     */
    public function init()
    {
        $this->addElementPrefixPath('Tri_Filter', 'Tri/Filter', 'FILTER');
        $this->addElementPrefixPath('Tri_Validate', 'Tri/Validate', 'VALIDATE');

        $user = new Tri_Db_Table('user');

        $validators = $user->getValidators();
        $filters    = $user->getFilters();
        $uploadDir  = Tri_Config::get('tri_upload_dir');

        $this->setAction('user/save')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data');

        $id = new Zend_Form_Element_Hidden('id');
        $id->addValidators($validators['id'])
                ->addFilters($filters['id'])
                ->removeDecorator('Label')
                ->removeDecorator('HtmlTag');

        $filters['name'][] = 'StripTags';
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name')
                ->addValidators($validators['name'])
                ->addFilters($filters['name'])
                ->setRequired()
                ->setAttrib('class', 'xlarge');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
                ->addValidators($validators['email'])
                ->addFilters($filters['email'])
                ->setRequired()
                ->setAttrib('class', 'xlarge');

        $filters['password'][] = array('Md5', Tri_Config::get('tri_salt'));
        $validators['password'][] = 'PasswordConfirmation';
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')
                ->addValidators($validators['password'])
                ->addFilters($filters['password'])
                ->setAttrib('id', 'passwordIn')
                ->setRequired();

        $passwordConfirmation = new Zend_Form_Element_Password('password_confirm');
        $passwordConfirmation->setLabel('Password confirmation')
                             ->addFilter('Md5', Tri_Config::get('tri_salt'))
                             ->setRequired();

        $sex = new Zend_Form_Element_Select('sex');
        $sex->setLabel('Sex')
            ->addValidators($validators['sex'])
            ->addFilters($filters['sex'])
            ->addMultiOptions(array('' => '[select]', 'M' => 'Male', 'F' => 'Female'));

        $born = new Zend_Form_Element_Text('born');
        $born->setLabel('Born')
             ->setAttrib('class', 'date small')
             ->addFilters($filters['born'])
             ->addValidators($validators['born']);

        $filters['description'][] = 'StripTags';
        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Description')
                    ->addValidators($validators['description'])
                    ->addFilters($filters['description'])
                    ->setAttrib('class', 'xxlarge');

        $file = new Zend_Form_Element_File('image');
        $file->setLabel('Image')
             ->setDestination($uploadDir)
             ->setMaxFileSize(2097152)//2mb
             ->setValueDisabled(true)
             ->addFilter('Rename', uniqid())
             ->addValidator('IsImage')
             ->addValidator('Count', false, 1)
             ->addValidator('Size', false, 2097152)//2mb
             ->addValidator('Extension', false, 'jpg,png,gif');

        $this->addElement($id)
             ->addElement($name)
             ->addElement($email)
             ->addElement($password)
             ->addElement($passwordConfirmation)
             ->addElement($description)
             ->addElement($sex)
             ->addElement($born)
             ->addElement($file);
        
        $this->addElement('submit', 'Save', array('class' => 'btn primary'));
    }
}
