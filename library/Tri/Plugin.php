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
 * @package    Tri_Plugin
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Tri_Plugin
{
    const CONFIG_FILE = "configuration.xml";
    /**
     *
     * @var array
     */
    public static $_data;
    
    /**
     *
     * @var string 
     */
    protected $_name;
    
    /**
     * 
     * @var string
     */
    protected $_path;
    
    /**
     * 
     * @var mixed
     */
    protected $_config = false;

    /**
     *
     * @param string $name 
     */
    public function __construct($name) 
    {
        $this->_name = $name;
        $this->_path = APPLICATION_PATH . '/../plugins/' . $name . '/';
    }
    
    /**
     *
     * @return SimpleXMLObject
     */
    public function getConfiguration() 
    {
        if (!$this->_config) {
            $filename = $this->_path . self::CONFIG_FILE;
            if (!file_exists($filename)) {
                throw new Exception('No configuration file (configuration.xml)');
            }
            $this->_config = simplexml_load_file($filename);
        }
        return $this->_config;
    }

        /**
     * Checks if the plugin is active
     *
     * @param string $name
     */
    public static function isActive($name)
    {
        $activedPlugins = Tri_Config::get('tri_plugins', true);
        if (in_array($name, $activedPlugins)) {
            return true;
        }
        return false;
    }
    
    /**
     * Checks if the plugin is active
     *
     * @param string $name
     */
    public static function isInstall($name)
    {
        $installed = Tri_Config::get('tri_plugins_installed', true);
        if (in_array($name, $installed)) {
            return true;
        }
        return false;
    }
    
    /**
     *
     * @return type 
     */
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

    protected function _addMenu($name, $group, $item, $resource)
    {
        $item  = strtolower($item);
        $itens = Tri_Config::get($name, true);
        $itens[$group][$item] = str_replace('/','.', $resource);
        Tri_Config::set($name, $itens, true);
    }

    protected function _removeMenu($name, $group, $item)
    {
        $item  = strtolower($item);
        $itens = Tri_Config::get($name, true);
        unset($itens[$group][$item]);
        Tri_Config::set($name, $itens, true);
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

    public function activate()
    {
        $actived = Tri_Config::get('tri_plugins', true);
        
        if (!self::isInstall($this->_name)) {
            $this->install();
        }
        
        $config = $this->getConfiguration();
        
        //add menus
        if ($config->menus) {
            foreach ($config->menus->menu as $menu) {
                foreach ($menu->item as $item) {
                    $this->_addMenu((string) $menu['type'], 
                                    (string) $item['group'], 
                                    (string) $item, 
                                    (string) $item['resource']);
                }
            }
        }
        
        //add access
        if ($config->access) {
            foreach ($config->access->resource as $resource) {
                $this->_addAclItem((string) $resource, (string) $resource['role']);
            }
        }
        
        //add configuration
        if ($config->configuration) {
            foreach ($config->configuration->item as $item) {
                Tri_Config::set((string) $item['name'], (string) $item);
            }
        }
        
        $actived[] = $this->_name;
        $actived   = array_unique($actived);
        
        Tri_Config::set('tri_plugins', $actived, true);
    }

    public function desactivate()
    {
        $activedPlugins = Tri_Config::get('tri_plugins', true);
        
        $config = $this->getConfiguration();
        
        //remove menus
        if ($config->menus) {
            foreach ($config->menus->menu as $menu) {
                foreach ($menu->item as $item) {
                    $this->_removeMenu((string) $menu['type'], 
                                       (string) $item['group'], 
                                       (string) $item);
                }
            }
        }
        
        //remove access
        if ($config->access) {
            foreach ($config->access->resource as $resource) {
                $this->_removeAclItem((string) $resource);
            }
        }
        
        foreach($activedPlugins as $key => $value) {
            if ($value == $this->_name) {
                unset($activedPlugins[$key]);
            }
        }

        Tri_Config::set('tri_plugins', $activedPlugins, true);
    }

    public function install()
    {
        $installed = Tri_Config::get('tri_plugins_installed', true);
            
        $config = $this->getConfiguration();
        $files = $config->files;
        
        //exec db files
        if ($files->sql->install) {
            $filename = $this->_path . $files->sql->install;
            $sql = file_get_contents($filename);
            
            $this->_getDb()->exec($sql);
        }
        
        //copy languages files
        if ($files->languages) {
            if (Zend_Translate::hasCache()) {
                Zend_Translate::clearCache();
            }
            foreach ($files->languages->language as $language) {
                $source = $this->_path . $language;
                $folder = $language['name'];
                $dest   = APPLICATION_PATH . '/../data/language/' . $folder . '/' . $this->_name . '.csv';
                
                copy($source, $dest);
            }
        }
        
        $installed[] = $this->_name;
        $installed   = array_unique($installed);
        Tri_Config::set('tri_plugins_installed', $installed, true);
    }
    
    public function uninstall()
    {
        $installed = Tri_Config::get('tri_plugins_installed', true);
            
        $config = $this->getConfiguration();
        $files = $config->files;
        
        //exec db files
        if ($files->sql->uninstall) {
            $filename = $this->_path . $files->sql->uninstall;
            $sql = file_get_contents($filename);
            
            $this->_getDb()->exec($sql);
        }
        
        //copy languages files
        if ($files->languages) {
            if (Zend_Translate::hasCache()) {
                Zend_Translate::clearCache();
            }
            foreach ($files->languages->language as $language) {
                $folder   = $language['name'];
                $filename = APPLICATION_PATH . '/../data/language/' . $folder . '/' . $this->_name . '.csv';
                
                @unlink($filename);
            }
        }
        
        foreach($installed as $key => $value) {
            if ($value == $this->_name) {
                unset($installed[$key]);
            }
        }
        
        Tri_Config::set('tri_plugins_installed', $installed, true);
    }
}