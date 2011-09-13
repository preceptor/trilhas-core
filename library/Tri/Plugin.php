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
class Tri_Plugin extends Tri_Installation
{
    /**
     *
     * @var string 
     */
    protected $_name;

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
     * Checks if the plugin is active
     *
     * @param string $name
     */
    public static function isActive($name)
    {
        $activedPlugins = Tri_Config::get('tri_plugins', true);
        if (is_array($activedPlugins) && in_array($name, $activedPlugins)) {
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
    
    public function activate()
    {
        $actived = Tri_Config::get('tri_plugins', true);
        
        if (!self::isInstall($this->_name)) {
            $this->install();
        }
        
        parent::activate();
        
        $actived[] = $this->_name;
        $actived   = array_unique($actived);
        
        Tri_Config::set('tri_plugins', $actived, true);
    }

    public function desactivate()
    {
        $activedPlugins = Tri_Config::get('tri_plugins', true);
        
        parent::desactivate();
        
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
            
        parent::install();
        
        $installed[] = $this->_name;
        $installed   = array_unique($installed);
        Tri_Config::set('tri_plugins_installed', $installed, true);
    }
    
    public function uninstall()
    {
        $installed = Tri_Config::get('tri_plugins_installed', true);
            
        parent::uninstall();
        
        foreach($installed as $key => $value) {
            if ($value == $this->_name) {
                unset($installed[$key]);
            }
        }
        
        Tri_Config::set('tri_plugins_installed', $installed, true);
    }
}