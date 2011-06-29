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
class Admin_Form_Classroom extends Zend_Form
{
    /**
     * (non-PHPdoc)
     * @see Zend_Form#init()
     */
    public function init()
    {
        $this->addElementPrefixPath('Tri_Filter', 'Tri/Filter', 'FILTER');
        
        $table  = new Tri_Db_Table('classroom');
        $course = new Tri_Db_Table('course');
        $user   = new Tri_Db_Table('user');

        $validators = $table->getValidators();
        $filters    = $table->getFilters();
        $where      = array("role = 'institution' OR role = 'coordinator'");
        $users      = $user->fetchPairs('id', 'name', $where, 'name');
        
        $select     = $course->select(true)
                             ->join('user', 'course.user_id = user.id', array())
                             ->where("course.status='active'");
                        
        $courses    = $course->fetchPairs('id', 'name', $select);

        $statusOptions       = array('active' => 'active', 'inactive' => 'inactive','open' => 'open');
        $visibilityOptions   = array('public' => 'public', 'protected' => 'protected', 'private' => 'private');
        $registerTypeOptions = array('open' => 'open');

        if (Tri_Plugin::isActive('selection-process')) {
            $registerTypeOptions['process'] = 'selection process';
        }

        if (Tri_Plugin::isActive('payment')) {
            $registerTypeOptions['payment'] = 'payment form';
        }

        $this->setAction('admin/classroom/save')
             ->setMethod('post')
             ->setAttrib('enctype', 'multipart/form-data');

        $id = new Zend_Form_Element_Hidden('id');
        $id->addValidators($validators['id'])
           ->addFilters($filters['id'])
           ->removeDecorator('Label')
           ->removeDecorator('HtmlTag');

        $courseId = new Zend_Form_Element_Select('course_id');
        $courseId->setLabel('Course')
                 ->addValidators($validators['course_id'])
                 ->addFilters($filters['course_id'])
                 ->addMultiOptions(array('' => '[select]') + $courses);

        $filters['name'][] = 'StripTags';
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name')
             ->addValidators($validators['name'])
             ->addFilters($filters['name']);

        $responsible = new Zend_Form_Element_Select('responsible');
        $responsible->setLabel('Responsible')
                    ->addValidators($validators['responsible'])
                    ->addFilters($filters['responsible'])
                    ->addMultiOptions(array('' => '[select]') + $users);

        $begin = new Zend_Form_Element_Text('begin');
        $begin->setLabel('Begin')
              ->setAttrib('class', 'date')
              ->addFilters($filters['begin'])
              ->addValidators($validators['begin'])
              ->setAllowEmpty(false);

        $end = new Zend_Form_Element_Text('end');
        $end->setLabel('End')
            ->setAttrib('class', 'date')
            ->addFilters($filters['end']);

        $max = new Zend_Form_Element_Text('max_student');
        $max->setLabel('Max student')
             ->addValidators($validators['max_student'])
             ->addFilters($filters['max_student']);

        $amount = new Zend_Form_Element_Text('amount');
        $amount->setLabel('Amount')
               ->addValidators($validators['amount'])
               ->addFilters($filters['amount']);
        
        $status = new Zend_Form_Element_Select('status');
        $status->addMultiOptions($statusOptions)
               ->setLabel('Status')
               ->addValidators($validators['status'])
               ->addFilters($filters['status']);

        $visibility = new Zend_Form_Element_Select('visibility');
        $visibility->addMultiOptions($visibilityOptions)
                   ->setLabel('Visibility');

        $registerType = new Zend_Form_Element_Select('register_type');
        $registerType->addMultiOptions($registerTypeOptions)
                     ->setLabel('Register type');

        $this->addElement($id)
             ->addElement($courseId)
             ->addElement($name)
             ->addElement($responsible)
             ->addElement($begin)
             ->addElement($end)
             ->addElement($max)
             ->addElement($visibility)
             ->addElement($registerType)
             ->addElement($amount)
             ->addElement($status)
             ->addElement('submit', 'Save');
   }
}
