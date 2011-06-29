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
class Content_Form_Panel extends Zend_Form
{
    /**
     * (non-PHPdoc)
     * @see Zend_Form#init()
     */
    public function init()
    {
        $table      = new Tri_Db_Table('restriction_panel');
        $panel      = new Tri_Db_Table('panel');
        $validators = $table->getValidators();
        $filters    = $table->getFilters();
        $session    = new Zend_Session_Namespace('data');
        $contents   = $this->toSelectContent(Zend_Json::decode($session->contents));
        $select     = $panel->select(true)->columns("CONCAT(type, ' - ', item_id) as typeId")
                                      ->where('classroom_id = ?', $session->classroom_id);
        $panels     = $panel->fetchPairs('id', 'typeId', $select);

        $this->setAction('content/restriction/panel-save')
             ->setMethod('post');

        $id = new Zend_Form_Element_Hidden('id');
        $id->addValidators($validators['id'])
           ->addFilters($filters['id'])
           ->removeDecorator('Label')
           ->removeDecorator('HtmlTag');

        $note = new Zend_Form_Element_Text('note');
        $note->setLabel('Note')
              ->addValidators($validators['note'])
              ->addFilters($filters['note']);
        
        $noteRestriction = new Zend_Form_Element_Text('note_restriction');
        $noteRestriction->setLabel('Free note')
                        ->addValidators($validators['note_restriction'])
                        ->addFilters($filters['note_restriction']);

        $validators['content_id'][] = array('GreaterThan', false, array('min' => 1));
        $contentId = new Zend_Form_Element_Select('content_id');
        $contentId->setLabel('Content')
                  ->addValidators($validators['content_id'])
                  ->addFilters($filters['content_id'])
                  ->addMultiOptions(array('' => '[select]') + $contents);
        
        $validators['panel_id'][] = array('GreaterThan', false, array('min' => 1));
        $panelId = new Zend_Form_Element_Select('panel_id');
        $panelId->setLabel('Item')
                ->addValidators($validators['content_id'])
                ->addFilters($filters['content_id'])
                ->addMultiOptions(array('' => '[select]') + $panels);

        $this->addElement($id)
             ->addElement($note)
             ->addElement($noteRestriction)
             ->addElement($panelId)
             ->addElement($contentId)
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
