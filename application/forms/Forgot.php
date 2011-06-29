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
class Application_Form_Forgot extends Zend_Form
{
    /**
     * (non-PHPdoc)
     * @see Zend_Form#init()
     */
    public function init()
    {
		$user = new Tri_Db_Table('user');
		
		$this->addElementPrefixPath('Tri_Filter', 'Tri/Filter', 'FILTER');
        $this->addElementPrefixPath('Tri_Validate', 'Tri/Validate', 'VALIDATE');
		
		$validators = $user->getValidators();
		$filters = $user->getFilters();
		
        $this->setAction('forgot/send')
             ->setMethod('post');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
			  ->setRequired()
              ->addValidators($validators['email'])
              ->addFilters($filters['email']);

        $this->addElement($email)
             ->addElement('submit', 'Enviar');
   }
}
