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
class Tri_Application_Resource_Acl extends Zend_Application_Resource_ResourceAbstract
{
    const RESOURCE_SEPARATOR = '+';

    /**
     * @var Zend_Acl
     */
    protected $_acl;
    protected $_roles;
    protected $_resources;

    /**
     * (non-PHPdoc)
     * @see Zend_Application_Resource_ResourceAbstract#init()
     */
    public function init()
    {
        $this->_acl = new Zend_Acl();

        // static roles
        $this->_acl->addRole(new Zend_Acl_Role('all'));
        $this->_acl->addRole(new Zend_Acl_Role('anonymous'), 'all');
        $this->_acl->addRole(new Zend_Acl_Role('identified'), 'all');

        // dinamic roles
        foreach ($this->_roles as $roleName) {
            if (!$this->_acl->hasRole($roleName)) {
                $this->_acl->addRole(new Zend_Acl_Role($roleName), 'identified');
            }
        }
        
        // rules
        foreach ($this->_resources as $module => $grants) {
            $module = strtolower($module);
            $this->_acl->add(new Zend_Acl_Resource($module));
            foreach ($grants as $controller => $grant) {
                $controller = strtolower($controller);
                foreach ($grant as $action => $roles) {
                	$resource = $controller . self::RESOURCE_SEPARATOR . $action;
                	foreach (explode(',', $roles) as $role) {
	                    if (!empty($role)) {
	                        $this->_acl->allow(trim($role), $module, $resource);
	                    }
                	}
                }
            }
        }

        Zend_Registry::set('acl', $this->_acl);
        return $this->_acl;
    }

    public function setRoles(array $roles)
    {
        $this->_roles = $roles;
        return $this;
    }

    public function setResources(array $resources)
    {
        $this->_resources = $resources;
        return $this;
    }
}
