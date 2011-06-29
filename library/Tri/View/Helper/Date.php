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
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * @category   Tri
 * @package    Tri_View
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Tri_View_Helper_Date extends Zend_View_Helper_Abstract
{
    public function date($value, $displayTime = false)
    {
		if ($displayTime) {
            $translate = Zend_Registry::get('Zend_Translate');
            $locale    = key(Zend_Registry::get('Zend_Locale')->getDefault());
			$date      = new Zend_Date($value, null, $locale);

			if ($date->isToday()) {
                $h = $date->toString('H');
                $m = $date->toString('m');
                $s = $date->toString('s');

                if (date('H') == $h) {
                    $min = (int) date('i') - (int) $m;
                    if ($min < 1) {
                        $sec = (int) date('s') - (int) $s;
                        if ($sec < 10) {
                            return $translate->_('now');
                        }
                        return $sec . ' ' . $translate->_('seconds ago');
                    }
                    return $min . ' ' . $translate->_('minutes ago');
                } elseif (date('H') > $h) {
                    return (int) date('H') - (int) $h . ' ' . $translate->_('hours ago');
                }

				return $date->toString('H:m');
			} else {
				return $date->toString('dd/MM/y H:m');
			}
		}
        
		return Zend_Filter::filterStatic($value, 'date', array(), 'Tri_Filter');
    }
}
