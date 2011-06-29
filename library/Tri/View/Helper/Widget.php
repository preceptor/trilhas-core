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
 * @package    Tri_View
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Tri_View_Helper_Widget extends Zend_View_Helper_Abstract
{
    public function widget($position)
    {
        $xhtml = '';

        $table = new Tri_Db_Table('widget');
        $widgets = $table->fetchAll(array('position = ?' => $position,
                                          'status = ?' => 'active'), 'order');

        if (count($widgets)) {
            foreach($widgets as $widget) {
                $xhtml .= $this->view->action($widget->action,
                                              $widget->controller,
                                              $widget->module,
                                              Zend_Controller_Front::getInstance()->getRequest()->getUserParams());
            }
        }

        return $xhtml;
    }
}