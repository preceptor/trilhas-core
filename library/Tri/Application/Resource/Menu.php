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
 * @category   Tri
 * @package    Tri_Application
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Tri_Application_Resource_Menu extends Zend_Application_Resource_ResourceAbstract
{
    const RESOURCE_SEPARATOR = '+';

    protected $_names;

    /**
     * (non-PHPdoc)
     * @see Zend_Application_Resource_ResourceAbstract#init()
     */
    public function init()
    {
        foreach ($this->_names as $name => $parent) {
            foreach ($parent as $child => $value) {
                $data = explode('.', $value);
                $menu[$name][$child]['module']     = $data[0];
                $menu[$name][$child]['controller'] = $data[1];
                $menu[$name][$child]['action']     = $data[2];
            }
        }

        Zend_Registry::set('menu', $menu);
        return $menu;
    }

    public function setNames(array $names)
    {
        $this->_names = $names;
        return $this;
    }
}
