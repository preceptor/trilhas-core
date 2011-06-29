<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Abstract.php 20096 2010-01-06 02:05:09Z bkarwin $
 */

/**
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class Tri_Plugin_Abstract implements Tri_Plugin_Interface
{
    protected $_name;

    protected function _getDb()
    {
        return Zend_Db_Table::getDefaultAdapter();
    }

    protected function _addAclItem($resource, $role)
    {
        $itens = explode('/', $resource);
        $resources = Tri_Config::get('tri_resources', true);

        $resources[$itens[0]][$itens[1]][$itens[2]] = $role;

        Tri_Config::set('tri_resources', $resources, true);
    }

    protected function _removeAclItem($resource)
    {
        $itens = explode('/', $resource);
        $resources = Tri_Config::get('tri_resources', true);

        unset($resources[$itens[0]][$itens[1]][$itens[2]]);

        if (isset($resources[$itens[0]][$itens[1]])
             && !count($resources[$itens[0]][$itens[1]])) {
            unset($resources[$itens[0]][$itens[1]]);
        }

        if (isset($resources[$itens[0]]) && !count($resources[$itens[0]])) {
            unset($resources[$itens[0]]);
        }

        Tri_Config::set('tri_resources', $resources, true);
    }

    protected function _addClassroomMenuItem($category, $name, $resources)
    {
        $itens = Tri_Config::get('tri_classroom_menu', true);
        $itens[$category][$name] = str_replace('/','.', $resources);
        Tri_Config::set('tri_classroom_menu', $itens, true);
    }

    protected function _removeClassroomMenuItem($category, $name)
    {
        $itens = Tri_Config::get('tri_classroom_menu', true);
        unset($itens[$category][$name]);
        Tri_Config::set('tri_classroom_menu', $itens, true);
    }

    protected function _addAdminMenuItem($category, $name, $resources)
    {
        $itens = Tri_Config::get('tri_admin_menu', true);
        $itens[$category][$name] = str_replace('/','.', $resources);
        Tri_Config::set('tri_admin_menu', $itens, true);
    }

    protected function _removeAdminMenuItem($category ,$name)
    {
        $itens = Tri_Config::get('tri_admin_menu', true);
        unset($itens[$category][$name]);
        Tri_Config::set('tri_admin_menu', $itens, true);
    }

    protected function _addDashboardMenuItem($name, $resources)
    {
        $itens = Tri_Config::get('tri_dashboard_menu', true);
        $itens[$name] = str_replace('/','.', $resources);
        Tri_Config::set('tri_dashboard_menu', $itens, true);
    }

    protected function _removeDashboardMenuItem($name)
    {
        $itens = Tri_Config::get('tri_dashboard_menu', true);
        unset($itens[$name]);
        Tri_Config::set('tri_dashboard_menu', $itens, true);
    }

    protected function _addWidget($position, $module, $controller, $action, $order = 1)
    {
        $table = new Tri_Db_Table('widget');
        $table->createRow(array('position' => $position,
                                'module' => $module,
                                'controller' => $controller,
                                'action' => $action,
                                'order' => $order))->save();
    }

    protected function _removeWidget($position, $module, $controller, $action)
    {
        $table = new Tri_Db_Table('widget');
        $table->delete(array('position = ?' => $position,
                             'module = ?' => $module,
                             'controller = ?' => $controller,
                             'action = ?' => $action));
    }

    final public function _install()
    {
        $this->install();
    }

    final public function _activate()
    {
        $this->activate();

        $activedPlugins   = Tri_Config::get('tri_plugins', true);
        $activedPlugins[] = $this->_name;
        $activedPlugins   = array_unique($activedPlugins);
        
        Tri_Config::set('tri_plugins', $activedPlugins, true);
    }

    final public function _desactivate()
    {
        $this->desactivate();

        $activedPlugins = Tri_Config::get('tri_plugins', true);
        foreach($activedPlugins as $key => $value) {
            if ($value == $this->_name) {
                unset($activedPlugins[$key]);
            }
        }

        Tri_Config::set('tri_plugins', $activedPlugins, true);
    }

    public function install(){}

    public function activate(){}

    public function desactivate(){}
}