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
 * @package    Tri_Controller
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Tri_Controller_Plugin_Log extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        if ($identity && $identity->role != 'institution') {
            $log      = new Tri_Db_Table_Log();
            $session  = new Zend_Session_Namespace('data');
            
            $data = array('module' => $request->getModuleName(),
                          'controller' => $request->getControllerName(),
                          'action' => $request->getActionName(),
                          'user_id' => $identity->id);

            if ($session->classroom_id) {
                $data['classroom_id'] = $session->classroom_id;
            }
            
            $log->createRow($data)->save();
        }
    }
}