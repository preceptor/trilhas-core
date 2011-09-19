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
class Application_Form_Theme extends Zend_Form
{
    /**
     * (non-PHPdoc)
     * @see Zend_Form#init()
     */
    public function init()
    {
        $this->addElementPrefixPath('Tri_Filter', 'Tri/Filter', 'FILTER');
        $this->addElementPrefixPath('Tri_Validate', 'Tri/Validate', 'VALIDATE');

        $uploadDir = Tri_Config::get('tri_upload_dir');
        $styles    = Tri_Config::get('tri_theme_styles', true);

        $this->setAction('default/theme/save')
             ->setMethod('post')
             ->setAttrib('enctype', 'multipart/form-data');

        $style = new Zend_Form_Element_Select('style');
        $style->setLabel('Style')
              ->addMultiOptions($styles);

        $logo = new Zend_Form_Element_File('logo');
        $logo->setLabel('Logo')
             ->setDestination($uploadDir)
             ->setMaxFileSize(2097152)//2mb
             ->setValueDisabled(true)
             ->addValidator('IsImage')
             ->addValidator('ImageSize', false, array('maxwidth' => 200))
             ->addValidator('Count', false, 1)
             ->addValidator('Size', false, 2097152)//2mb
             ->addValidator('Extension', false, 'jpg,png,gif');
        
        $css = new Zend_Form_Element_Textarea('custom_css');
        $css->setLabel('Custom css')
            ->setAttrib('rows', 10)
            ->setAttrib('class', 'xxlarge');

        $this->addElement($style)
             ->addElement($logo)
             ->addElement($css);
        
        $this->addElement('submit', 'Save', array('class' => 'btn primary'));
    }

}
