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
 * @package    Admin_Form
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Admin_Form_User extends Tri_Form
{
    /**
     * (non-PHPdoc)
     * @see Zend_Form#init()
     */
    public function init()
    {
        $table         = new Tri_Db_Table_User();
        $validators    = $table->getValidators();
        $filters       = $table->getFilters();
        $statusOptions = array('' => '[select]', 'active' => 'active', 'inactive' => 'inactive');
        $roles         = array('' => '[select]', 'student' => 'student', 'teacher' => 'teacher', 'coordinator' => 'coordinator', 'institution' => 'institution');
        $uploadDir     = Tri_Config::get('tri_upload_dir');

        $this->setAction('admin/user/save')
             ->setMethod('post')
             ->setAttrib('enctype', 'multipart/form-data');

        $id = new Zend_Form_Element_Hidden('id');
        $id->removeDecorator('Label')
           ->removeDecorator('HtmlTag');

        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name')
             ->setAttrib('class', 'xlarge')
             ->setAllowEmpty(false);

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
              ->setAttrib('class', 'xlarge')
              ->setAllowEmpty(false);

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password');

        $passwordConfirmation = new Zend_Form_Element_Password('password_confirm');
        $passwordConfirmation->setLabel('Password confirmation');

        $sex = new Zend_Form_Element_Select('sex');
        $sex->setLabel('Sex')
            ->addMultiOptions(array('' => '[select]', 'M' => 'Male', 'F' => 'Female'));

        $born = new Zend_Form_Element_Text('born');
        $born->setLabel('Born')
             ->setAttrib('class', 'date small');

        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Description')
                    ->setAttrib('rows', '5')
                    ->setAttrib('cols', '92')
                    ->setAllowEmpty(false);

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

        $role = new Zend_Form_Element_Select('role');
        $role->addMultiOptions($roles)
             ->setRequired()
             ->setLabel('Role');

        $status = new Zend_Form_Element_Select('status');
        $status->addMultiOptions($statusOptions)
               ->setRequired()
               ->setLabel('Status');

        $this->addElement($id)
             ->addElement($name)
             ->addElement($email)
             ->addElement($password)
             ->addElement($passwordConfirmation)
             ->addElement($description)
             ->addElement($sex)
             ->addElement($born)
             ->addElement($file)
             ->addElement($status)
             ->addElement($role)
             ->addElement('submit', 'Save', array('class' => 'btn primary'));
        
        $this->addFilters($filters)
             ->addValidators($validators);
    }
    
    public function setPasswordRequired($id)
    {
        if (!$id) {
            $this->getElement('password')->setRequired();
        }
    }

    public function populate(array $values) 
    {
        $id = isset($values['id']) ? $values['id'] : 0;
        $this->setPasswordRequired($id);
        return parent::populate($values);
    }
    
    public function isValid($data) 
    {
        $id = isset($data['id']) ? $data['id'] : 0;
        $this->setPasswordRequired($id);
        return parent::isValid($data);
    }
}
