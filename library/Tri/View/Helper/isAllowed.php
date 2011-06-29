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
class Tri_View_Helper_IsAllowed extends Zend_View_Helper_Abstract
{
    /**
     * Check user has permission to passed resource
     *
     * @param array $data
     * @return boolean
     */
    public function isAllowed($data)
    {
        $acl      = Zend_Registry::get('acl');
        $identity = Zend_Auth::getInstance()->getIdentity();
        $role     = $identity->role;

        if (is_array($data)) {
            $privilege = $data['controller'] . Tri_Application_Resource_Acl::RESOURCE_SEPARATOR .
                         $data['action'];
            $resource = $data['module'];
            if ($acl->has($resource)) {
                if ($acl->isAllowed($role, $resource, $privilege)) {
                    return true;
                }
            }
        } else {
            $front     = Zend_Controller_Front::getInstance();
            $resource  = $front->getRequest()->getModuleName();
            $privilege = $front->getRequest()->getControllerName()
                       . Tri_Application_Resource_Acl::RESOURCE_SEPARATOR
                       . $data;
            if ($acl->has($resource)) {
                if ($acl->isAllowed($role, $resource, $privilege)) {
                    return true;
                }
            }
        }
        return false;
    }
}
