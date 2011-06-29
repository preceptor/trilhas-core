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

class Tri_View_Helper_Title extends Zend_View_Helper_Abstract
{
    public function title($text, $reference = null)
    {
        $xhtml = "";

        if ($text) {
            if (is_array($text)) {
                $titles = array();
                foreach ($text as $title) {
                    $titles[] = $this->view->translate($title);
                }
                $title = implode(' - ', $titles);
            } else {
                $title = $this->view->translate($text);
            }

            if ($reference) {
                $xhtml .= '<script type="text/javascript">';
                $xhtml .= '$("'.$reference.'").parents(".box")
                                              .find("h3.title")
                                              .text("' . $title . '");';

                $xhtml .= '</script>';
            } else {
                return $title;
            }
        }
        return $xhtml;
    }
}