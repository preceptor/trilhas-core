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

class Tri_View_Helper_Ajax extends Zend_View_Helper_Abstract
{
    public function ajax($text, $url, $type = 1, $confirm = NULL)
    {
        $id = uniqid();
        if ($type == 1) {
            $xhtml = '<a id="'.$id.'" href="' . $url . '">';
            $xhtml .= $this->view->translate($text);
            $xhtml .= '</a>';
        } else {
            $primary = null;
            if ($type == 3) {
                $primary = 'primary';
            }
            $xhtml = '<input class="btn '.$primary.'" id="'.$id.'" type="button" ';
            $xhtml .= ' value="' . $this->view->translate($text) . '"';
            $xhtml .= ' />';
        }

        $xhtml .= '<script type="text/javascript">';
        $xhtml .= '$("#'.$id.'").click(function(){ ';
            
        if ($confirm) {
            $xhtml .= 'if( confirm("' . $this->view->translate($confirm) . '")) {';
        }

        $xhtml .= '$(this).parents(".content").load("' . $url . '");';

        if ($confirm) {
            $xhtml .= '}';
        }

        $xhtml .= 'return false; });';
        $xhtml .= '</script>';
        
        return $xhtml;
    }

}

?>