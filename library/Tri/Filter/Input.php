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
 * @package    Tri_Db
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Tri_Filter_Input extends Zend_Filter_Input
{
    public function __construct($filterRules, $validatorRules, array $data = null, array $options = null) 
    {
        $defaults = array(Zend_Filter_Input::FILTER_NAMESPACE => 'Tri_Filter',
                          Zend_Filter_Input::VALIDATOR_NAMESPACE =>'Tri_Validate',
                          Zend_Filter_Input::ALLOW_EMPTY => true);
        $options = array_merge((array) $options, $defaults);                  
        parent::__construct($filterRules, $validatorRules, $data, $options);
    }


    /**
     * @param string $type
     * @param mixed $classBaseName
     * @return Zend_Filter_Interface|Zend_Validate_Interface
     * @throws Zend_Filter_Exception
     */
    protected function _getFilterOrValidator($type, $classBaseName)
    {
        $args = array();

        if (is_array($classBaseName)) {
            $args = $classBaseName;
            $classBaseName = array_shift($args);
            // adding for form validation compatibility
            $ignored = array_shift($args);
        }

        $interfaceName = 'Zend_' . ucfirst($type) . '_Interface';
        $className = $this->getPluginLoader($type)->load(ucfirst($classBaseName));

        $class = new ReflectionClass($className);

        if (!$class->implementsInterface($interfaceName)) {
            require_once 'Zend/Filter/Exception.php';
            throw new Zend_Filter_Exception("Class '$className' based on basename '$classBaseName' must implement the '$interfaceName' interface");
        }

        if ($class->hasMethod('__construct')) {
            $object = $class->newInstanceArgs($args);
        } else {
            $object = $class->newInstance();
        }

        return $object;
    }
    
    
}
