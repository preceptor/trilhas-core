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
 * @category   Migration
 * @package    Migration_Form
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Migration_Form_Content extends Tri_Form
{
    /**
     * (non-PHPdoc)
     * @see Zend_Form#init()
     */
    public function init()
    {
        $uploadDir = Tri_Config::get('tri_upload_dir');

        $this->setAction('migration/content/import')
             ->setMethod('post')
             ->setAttrib('enctype', 'multipart/form-data');

        $id = new Zend_Form_Element_Hidden('id');
        $id->removeDecorator('Label')
           ->removeDecorator('HtmlTag');

        $file = new Zend_Form_Element_File('location');
        $file->getPluginLoader('filter')->addPrefixPath('Tri_Filter', 'Tri/Filter');
        $file->setLabel('File')
             ->setDestination(APPLICATION_PATH . '/../data/upload/')
             ->setMaxFileSize(10485760)//10mb
             ->setValueDisabled(true)
             ->addFilter('Rename', $this->_getNewName($file->getFileName()))
             ->addValidator('Count', false, 1)
             ->addValidator('Size', false, 10485760)//10mb
             ->addValidator('Extension', false, 'zip');

        $this->addElement($id)
             ->addElement($file)
             ->addElement('submit', 'Import', array('class' => 'btn primary'));
    }
    
    private function _getNewName($name)
    {
        if ($name) {
           $newName = ucwords(strtolower(str_replace(array('.','-','_'), ' ', pathinfo($name, PATHINFO_FILENAME))));
           $newName = Zend_Filter::filterStatic($newName, 'Alnum');
           return $newName . '-' . uniqid() . '.' . pathinfo($name, PATHINFO_EXTENSION);
        }
        return '';
    }
}
