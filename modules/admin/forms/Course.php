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
 * @package    Aadmin_Form
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Admin_Form_Course extends Zend_Form
{
    /**
     * (non-PHPdoc)
     * @see Zend_Form#init()
     */
    public function init()
    {
        $course    = new Tri_Db_Table('course');
        $user      = new Tri_Db_Table('user');
        $uploadDir = str_replace('APPLICATION_PATH', APPLICATION_PATH, Tri_Config::get('tri_upload_dir'));

        $validators    = $course->getValidators();
        $filters       = $course->getFilters();
        $where         = array("role = 'institution' OR role = 'Teacher' OR role = 'Creator'");
        $users         = $user->fetchPairs('id', 'name', $where, 'name');
        $statusOptions = array('active' => 'active', 'inactive' => 'inactive');
//        $categories    = $course->fetchPairs('category', 'category');

        $this->setAction('admin/course/save')
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
             ->setAttrib('class', 'xlarge')
             ->setRequired();

        $responsible = new Zend_Form_Element_Select('responsible');
        $responsible->setLabel('Responsible')
                    ->addValidators($validators['responsible'])
                    ->addFilters($filters['responsible'])
                    ->addMultiOptions(array('' => '[select]') + $users);

        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Description')
                    ->addValidators($validators['description'])
                    ->addFilters($filters['description'])
                    ->setAttrib('class', 'xxlarge')
                    ->setAllowEmpty(false);

        $information = new Zend_Form_Element_Textarea('information');
        $information->setLabel('Information')
                    ->addValidators($validators['information'])
                    ->addFilters($filters['information'])
                    ->setAttrib('id', 'course-information-text')
                    ->setAllowEmpty(false);

        $filters['hours'][] = 'StripTags';
        $hours = new Zend_Form_Element_Text('hours');
        $hours->setLabel('Hours')
              ->setRequired()
              ->addValidators($validators['hours'])
              ->addFilters($filters['hours'])
              ->setAttrib('class', 'small');

//        if (!$categories || isset($categories[''])) {
//            $category = new Zend_Form_Element_Text('category');
//        } else {
//            $categories = array_unique($categories);
//            $category   = new Zend_Form_Element_Select('category');
//            $category->addMultiOptions(array('' => '[select]') + $categories)
//                     ->setRegisterInArrayValidator(false);
//        }
//        $category->setLabel('Category')
//                 ->addValidators($validators['category'])
//                 ->addFilters($filters['category']);

        $file = new Zend_Form_Element_File('image');
        $file->setLabel('Image')
             ->setDestination($uploadDir)
             ->setMaxFileSize(2097152)//2mb
             ->setValueDisabled(true)
             ->addFilter('Rename', uniqid())
             ->addValidator('Count', false, 1)
             ->addValidator('Size', false, 2097152)//2mb
             ->addValidator('Extension', false, 'jpg,png,gif');

        $status = new Zend_Form_Element_Select('status');
        $status->addMultiOptions($statusOptions)
               ->setRegisterInArrayValidator(false)
               ->setLabel('Status')
               ->addValidators($validators['status'])
               ->addFilters($filters['status']);

        $this->addElement($id)
             ->addElement($name)
             ->addElement($description)
             ->addElement($information)
             ->addElement($responsible)
             ->addElement($hours)
//             ->addElement($category)
             ->addElement($file)
             ->addElement($status)
             ->addElement('submit', 'Save', array('class' => 'btn primary'));
   }
}
