<?php
/**
 * Trilhas - Learning Management System
 * Copyright (C) 2005-2011  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
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
 * @package    Migration_Bootstrap
 * @copyright  Copyright (C) 2005-2011  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Migration_Bootstrap extends Zend_Application_Module_Bootstrap
{
    public function _initServerUrl()
    {
        $serverUrl = new Zend_View_Helper_ServerUrl();
        $baseUrl   = $_SERVER['SCRIPT_NAME'];
        
        if (($pos = strripos($baseUrl, basename($baseUrl))) !== false) {
            $baseUrl = substr($baseUrl, 0, $pos);
        }
        
        $url = $serverUrl->serverUrl() . $baseUrl;
        
        define('SERVER_URL', $url);
    }
}
