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
 * @see Zend_Controller_Dispatcher_Standard
 */
require_once 'Zend/Controller/Dispatcher/Standard.php';

/**
 * @category   Tri
 * @package    Tri_Controller
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Tri_Controller_Dispatcher_Plugin extends Zend_Controller_Dispatcher_Standard
{
    protected $_themeController = null;
    
    public function setThemeController($path)
    {
        $this->_themeController = $path;
    }
    
    public function getThemeController()
    {
        return $this->_themeController;
    }
    
    /**
     * Load a controller class
     *
     * Attempts to load the controller class file from
     * {@link getControllerDirectory()}.  If the controller belongs to a
     * module, looks for the module prefix to the controller class.
     *
     * @param string $className
     * @return string Class name loaded
     * @throws Zend_Controller_Dispatcher_Exception if class not loaded
     */
    public function isDispatchable(Zend_Controller_Request_Abstract $request)
    {
        $className = $this->getControllerClass($request);
        if (!$className) {
            return false;
        }

        $finalClass  = $className;
        if (($this->_defaultModule != $this->_curModule)
            || $this->getParam('prefixDefaultModule'))
        {
            $finalClass = $this->formatClassName($this->_curModule, $className);
        }
        if (class_exists($finalClass, false)) {
            return true;
        }
        
        if ($this->_themeController && $this->_defaultModule == $this->_curModule) {
            $dispatchDir = $this->_themeController;
            $loadFile = $dispatchDir . DIRECTORY_SEPARATOR . $this->classToFilename($className);
            
            if (Zend_Loader::isReadable($loadFile)) {
                return true;
            }
        }

        $fileSpec    = $this->classToFilename($className);
        $dispatchDir = $this->getDispatchDirectory();
        $test        = $dispatchDir . DIRECTORY_SEPARATOR . $fileSpec;
        return Zend_Loader::isReadable($test);
    }
    
    public function loadClass($className)
    {
        $finalClass  = $className;
        if (($this->_defaultModule != $this->_curModule)
            || $this->getParam('prefixDefaultModule'))
        {
            $finalClass = $this->formatClassName($this->_curModule, $className);
        }
        if (class_exists($finalClass, false)) {
            return $finalClass;
        }

        if ($this->_themeController && $this->_defaultModule == $this->_curModule) {
            $dispatchDir = $this->_themeController;
            $loadFile = $dispatchDir . DIRECTORY_SEPARATOR . $this->classToFilename($className);
            
            if (Zend_Loader::isReadable($loadFile)) {
                include_once $loadFile;
                
                if (!class_exists($finalClass, false)) {
                    require_once 'Zend/Controller/Dispatcher/Exception.php';
                    throw new Zend_Controller_Dispatcher_Exception('Invalid controller class ("' . $finalClass . '")');
                }

                return $finalClass;
            }
        }
        
        $dispatchDir = $this->getDispatchDirectory();
        $loadFile = $dispatchDir . DIRECTORY_SEPARATOR . $this->classToFilename($className);

        if (Zend_Loader::isReadable($loadFile)) {
            include_once $loadFile;
        } else {
            require_once 'Zend/Controller/Dispatcher/Exception.php';
            throw new Zend_Controller_Dispatcher_Exception('Cannot load controller class "' . $className . '" from file "' . $loadFile . "'");
        }

        if (!class_exists($finalClass, false)) {
            require_once 'Zend/Controller/Dispatcher/Exception.php';
            throw new Zend_Controller_Dispatcher_Exception('Invalid controller class ("' . $finalClass . '")');
        }

        return $finalClass;
    }
}