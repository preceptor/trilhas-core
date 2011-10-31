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
class Content_Form_Composer extends Zend_Form
{
    /**
     * (non-PHPdoc)
     * @see Zend_Form#init()
     */
    public function init()
    {
        $table      = new Tri_Db_Table('content');
        $validators = $table->getValidators();
        $filters    = $table->getFilters();
        $session    = new Zend_Session_Namespace('data');
        $contents   = $this->toSelectContent(Zend_Json::decode($session->contents));

        $this->setAction('content/composer/save')
             ->setMethod('post');

        $id = new Zend_Form_Element_Hidden('id');
        $id->addValidators($validators['id'])
           ->addFilters($filters['id'])
           ->removeDecorator('Label')
           ->removeDecorator('HtmlTag');

        $filters['title'][] = 'StripTags';
        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Title')
              ->setAttrib('class', 'xxlarge')
              ->addValidators($validators['title'])
              ->addFilters($filters['title']);
        
        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Description')
                    ->addValidators($validators['description'])
                    ->addFilters($filters['description'])
                    ->setAttrib('rows', 10)
                    ->setAttrib('id', 'composer-description-text')
                    ->setAllowEmpty(false);

        $content_id = new Zend_Form_Element_Select('content_id');
        $content_id->setLabel('Parent')
                    ->addValidators($validators['content_id'])
                    ->addFilters($filters['content_id'])
                    ->addMultiOptions(array('' => '[select]') + $contents);

        $this->addElement($id)
             ->addElement($title)
             ->addElement($content_id)
             ->addElement($description)
             ->addElement('submit', 'Save', array('class' => 'btn primary'));
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
