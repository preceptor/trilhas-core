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
 * @category   Application
 * @package    Application_Bootstrap
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initConfigResources()
    {
        $this->bootstrap('db');
        $options = array('resources' => Tri_Config::getAll(true, true));
        $defaultOptions = $this->getOptions();
        $this->setOptions($this->mergeOptions($options, $defaultOptions));
    
        if ($this->hasResource('cachemanager')){
            $this->bootstrap('cachemanager');
            $cache = $this->getResource('cachemanager')
                          ->getCache('default');
        
            $classFileIncCache = APPLICATION_PATH . '/../data/cache/pluginLoaderCache.php';
            if (file_exists($classFileIncCache)) {
                include_once $classFileIncCache;
            }

            if (APPLICATION_ENV == 'production') {
                Zend_Loader_PluginLoader::setIncludeFileCache($classFileIncCache);
                Zend_Db_Table::setDefaultMetadataCache($cache);
                Zend_Date::setOptions(array('cache' => $cache));
                Zend_Translate::setCache($cache);
                Zend_Locale::setCache($cache);
            }

            Zend_Registry::set('cache', $cache);
            Zend_Registry::set('Zend_Log', $this->bootstrap('log')
                                                ->getPluginResource('log')
                                                ->getLog());
        }
    }

    protected function _initAcl()
    {
        $acl = new Zend_Acl();
        $roles = Tri_Config::get('tri_roles', true);
        $resources = Tri_Config::get('tri_resources', true);

        // static roles
        $resource = new Tri_Application_Resource_Acl();
        $resource->setRoles($roles);
        $resource->setResources($resources);
        $resource->init();
        
    }

    protected function _initTheme()
    {
        $theme = Tri_Config::get('tri_theme');
        if ($theme != 'default') {
            $themePath  = APPLICATION_PATH . '/../themes/' . $theme . '/controllers';
            $view       = $this->bootstrap('view')->getResource('view');
            $front      = Zend_Controller_Front::getInstance();
            $dispatcher = new Tri_Controller_Dispatcher_Plugin();
           
            $dispatcher->setThemeController($themePath);
            $front->setDispatcher($dispatcher);
            
            $view->addScriptPath(APPLICATION_PATH . '/../themes/' . $theme . '/views/layouts');
            $view->addScriptPath(APPLICATION_PATH . '/../themes/' . $theme . '/views/scripts');
        }
    }
    
    protected function _initZFDebug()
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('ZFDebug');
		$this->bootstrap('frontcontroller');
        $front = $this->getResource('FrontController');

        if ($this->hasOption('zfdebug')) {
            $options = $this->getOption('zfdebug');
            if ($this->hasPluginResource('db')) {
                $this->bootstrap('db');
                $db = $this->getPluginResource('db')->getDbAdapter();
                $options['plugins']['Database']['adapter'] = $db;
            }

            if ($this->hasPluginResource('cachemanager')) {
                $this->bootstrap('cachemanager');
                $cache  = $this->getPluginResource('cachemanager')
                       ->getCacheManager()
                       ->getCache('default');
                $options['plugins']['Cache']['backend'] = $cache->getBackend();
            }
            
            $zfdebug = new ZFDebug_Controller_Plugin_Debug($options);
            $zfdebug->registerPlugin(new ZFDebug_Controller_Plugin_Debug_Plugin_Auth(array('user' => 'name')));
            $zfdebug->registerPlugin(new ZFDebug_Controller_Plugin_Debug_Plugin_Session());
            $front->registerPlugin($zfdebug);
        }
    }
}