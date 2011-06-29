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
 * @package    Application_Controller
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class ErrorController extends Zend_Controller_Action
{
	/**
	 * Action error.
	 *
	 * Implement layout for treatment of error.
	 *
	 * @return void
	 */
	public function errorAction()
	{
        $e = $this->_getParam('error_handler')->exception;

        try {
            Zend_Db_Table_Abstract::getDefaultAdapter()->rollBack();
        } catch (Exception $en) { }

		echo $e->getMessage();
        if (APPLICATION_ENV == 'development') {
            echo '<br /><br />';
            $traces = $e->getTrace();
            foreach ($traces  as $trace) {
                if (isset($trace['line'])) {
                    echo $trace['line'] . " => " . $trace['file'] . '<br />';
                }
            }
        }
        exit;
	}
}
