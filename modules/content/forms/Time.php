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
 * @package    Content_Form
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Content_Form_Time extends Zend_Form
{
    /**
     * (non-PHPdoc)
     * @see Zend_Form#init()
     */
    public function init()
    {
        $this->addElementPrefixPath('Tri_Filter', 'Tri/Filter', 'FILTER');
        
        $table      = new Tri_Db_Table('restriction_time');
        $validators = $table->getValidators();
        $filters    = $table->getFilters();
        $session    = new Zend_Session_Namespace('data');
        $contents   = $this->toSelectContent(Zend_Json::decode($session->contents));

        $this->setAction('content/restriction/time-save')
             ->setMethod('post');

        $id = new Zend_Form_Element_Hidden('id');
        $id->addValidators($validators['id'])
           ->addFilters($filters['id'])
           ->removeDecorator('Label')
           ->removeDecorator('HtmlTag');

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

        $validators['content_id'][] = array('GreaterThan', false, array('min' => 1));
        $content_id = new Zend_Form_Element_Select('content_id');
        $content_id->setLabel('Content')
                   ->addValidators($validators['content_id'])
                   ->addFilters($filters['content_id'])
                   ->addMultiOptions(array('' => '[select]') + $contents)
                   ->setAllowEmpty(false);

        $this->addElement($id)
             ->addElement($begin)
             ->addElement($end)
             ->addElement($content_id)
             ->addElement('submit', 'Save');
   }
   
   private function toSelectContent($data)
   {
        $select = array();
        if ($data) {
            foreach( $data as $key => $val ) {
                $select[$val['id']] = str_repeat('- ', $val['level']) . $val['title'];
            }
        }
        return $select;
    }
}
