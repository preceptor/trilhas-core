<?php

class Preceptor_Generator_Php_Model extends Preceptor_Generator_Php_Abstract
{

    public function _create($className, $tableName, $metadata, $primary, $relations, $dependents, $extends)
    {
        $class = new Zend_CodeGenerator_Php_Class();
        $class->setExtendedClass($extends);
        $docblock = new Zend_CodeGenerator_Php_Docblock(array(
                    'shortDescription' => 'Automatically generated data model',
                    'longDescription' => 'This class has been automatically generated based on the dbTable "' . $tableName . '" @ ' . strftime('%d-%m-%Y %H:%M')
                ));

        $class->setName($className)
                ->setDocblock($docblock);

        foreach ($metadata as $key => $meta)
        {
            $defaultValue = (isset($meta['DEFAULT']) && $meta['DEFAULT'] != '') ?
                    $meta['DEFAULT'] :
                    new Zend_CodeGenerator_Php_Property_DefaultValue("null");

            $propertyName = $this->camelize($key);

            $class
                    ->setProperty(array(
                        'name' => lcfirst($propertyName),
                        'visibility' => 'protected',
                        'defaultValue' => $defaultValue,
                        'docblock' => array(
                            'shortDescription' => 'Automatically generated from db'
                        )
                    ))
                    // Setter method
                    ->setMethod(array(
                        'name' => 'set' . ucfirst($propertyName),
                        'parameters' => array(
                            array('name' => 'value')
                        ),
                        'body' => '$this->' . lcfirst($propertyName) . ' = $value;' . "\n" . 'return $this;',
                        'docblock' => new Zend_CodeGenerator_Php_Docblock(array(
                            'shortDescription' => 'Set the ' . lcfirst($propertyName) . ' property',
                            'tags' => array(
                                new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
                                    'paramName' => 'value',
                                    'datatype' => 'string'
                                )),
                                new Zend_CodeGenerator_Php_Docblock_Tag_Return(array(
                                    'datatype' => $className
                                ))
                            )
                        ))
                    ))
                    // Getter method
                    ->setMethod(array(
                        'name' => 'get' . ucfirst($propertyName),
                        'body' => 'return $this->' . lcfirst($propertyName) . ';',
                        'docblock' => new Zend_CodeGenerator_Php_Docblock(array(
                            'shortDescription' => 'Get the ' . lcfirst($propertyName) . ' property',
                            'tags' => array(
                                new Zend_CodeGenerator_Php_Docblock_Tag_Return(array(
                                    'datatype' => 'string'
                                ))
                            )
                        ))
                    ));
        }

        $class->setProperty(array(
            'name' => 'installed',
            'visibility' => 'public',
            'docblock' => 'Installed flag. Remove to regenerate.',
            'defaultValue' => 1
        ));

        $file = new Zend_CodeGenerator_Php_File(array(
                    'classes' => array($class)
                ));

        return $file->generate();
    }

    public function generateAbstract($modelClassName)
    {
        $class = new Zend_CodeGenerator_Php_Class();
        $class->setAbstract(true);
        $docblock = new Zend_CodeGenerator_Php_Docblock(array(
                    'shortDescription' => 'Automatically generated data model',
                        //'longDescription'  => 'This class has been automatically generated based on the dbTable "' . $tableName . '" @ ' . strftime('%d-%m-%Y %H:%M')
                ));

        //$modelClassName = "Model_Abstract";

        $class->setName($modelClassName)
                ->setDocblock($docblock)
                ->setMethod(array(
                    'name' => 'setFromArray',
                    'parameters' => array(
                        array(
                            'name' => 'dados',
                        )
                    ),
                    'body' => '
                    foreach($dados as $key=>$d) {
                        $infl = new Zend_Db_Inflector();
                        // Make sure $camel is camel format and not underscore
                        $method = $infl->camelize($key);
                        if( method_exists($this, $method = "get".ucfirst($method)) )
                            $this->$method($d);
                    }
                    
                    return $this;
                ',
                ));
        $file = new Zend_CodeGenerator_Php_File(array(
                    'classes' => array($class)
                ));
        return $file->generate();
    }
}