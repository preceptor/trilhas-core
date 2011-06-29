<?php
class Preceptor_Generator_Php_Mapper extends Preceptor_Generator_Php_Abstract {

    public function _create($className, $modelName, $tableName, $metadata, $pks, $relations, $dependents, $extends = null) 
    {
        $data = '';

        foreach ($metadata as $key => $meta) {
            $data .= "\t" . '"' . $key . '" => $model->get' . $this->camelize($key) . '(),' . "\n";
        }

        $data = 'array(' . "\n" . $data . ');';

        $class = new Zend_CodeGenerator_Php_Class();
        $class->setExtendedClass($extends);

        $docblock = new Zend_CodeGenerator_Php_Docblock(array(
            'shortDescription' => 'Automatically generated data model',
            'longDescription'  => 'This class has been automatically generated based on the dbTable "' . $tableName . '" @ ' . strftime('%d-%m-%Y %H:%M')
        ));

        $docblock = new Zend_CodeGenerator_Php_Docblock(array(
                    'shortDescription' => 'Automatically generated data model',
                    'longDescription' => 'This class has been automatically generated based on the dbTable "' . $tableName . '" @ ' . strftime('%d-%m-%Y %H:%M')
                ));

        $class
                ->setName($className)
                ->setDocblock($docblock)
                ->setMethod(array(
                    'name' => 'save',
                    'parameters' => array(
                        array(
                            'name' => 'model',
                            'type' => $modelName
                        )
                    ),
                    'body' => '
                      $data = ' . $data . '

                        if (null == ($id == $model->getId)) {
                            unset(data["id"]);
                                $this->getDbTable()->insert($data);
                        } else {
                            $this->getDbTable()->update($data, array("id = ?" => $id));
                        }
                    ',
                    'docblock' => new Zend_CodeGenerator_Php_Docblock(array(
                        'shortDescription' => 'Set the property',
                        'tags' => array(
                            new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
                                'paramName' => 'value',
                                'datatype' => 'string'
                            )),
                            new Zend_CodeGenerator_Php_Docblock_Tag_Return(array(
                                'datatype' => $modelName
                            ))
                        )
                    ))
                ));

        $file = new Zend_CodeGenerator_Php_File(array(
            'classes' => array($class)
        ));

        return $file->generate();
    }

    public function generateAbstract ($modelClassName)
    {
        $class = new Zend_CodeGenerator_Php_Class();
        $class->setAbstract(true);
        $docblock = new Zend_CodeGenerator_Php_Docblock(array(
            'shortDescription' => 'Automatically generated data model',
            //'longDescription'  => 'This class has been automatically generated based on the dbTable "' . $tableName . '" @ ' . strftime('%d-%m-%Y %H:%M')
        ));

        //$modelClassName = "Model_Mapper_Abstract";
        //$modelClassName = 'W';
        /*
        Zend_Debug::dump($tableName);
        Zend_Debug::dump($className);
         *
         */
        $class->setName($modelClassName)
             ->setDocblock($docblock)
             ->setProperty(array(
                'name'         => '_dbTable',
                'visibility'   => 'protected',
                'defaultValue' => new Zend_CodeGenerator_Php_Property_DefaultValue("null"),
                'docblock'     => array(
                    'shortDescription' => 'Automatically generated from db'
                )

            ))
            ->setMethod(array(
                'name' => 'setDbTable',
                'parameters' => array(
                    array(
                        'name' => 'dbTable',
                        //'type' => $this->_options['model']['prefix'] . $name
                    )
                ),
                'body' => '
                    if (is_string($dbTable)) {
                        $dbTable = new $dbTable();
                    }
                    if (!$dbTable instanceof Zend_Db_Table_Abstract) {
                        throw new Exception("Invalid table data gateway provided");
                    }
                    $this->_dbTable = $dbTable;
                    return $this;
                ',
                //'$this->' . lcfirst($propertyName) . ' = $value;' . "\n" . 'return $this;',
                'docblock' => new Zend_CodeGenerator_Php_Docblock(array(
                   // 'shortDescription' => 'Set the ' . lcfirst($propertyName) . ' property',
                    'tags' => array(
                        new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
                            'paramName' => 'value',
                            'datatype' => 'string'
                        )),
                        new Zend_CodeGenerator_Php_Docblock_Tag_Return(array(
                            'datatype' => $modelClassName
                        ))
                    )
                ))
            ))
            ->setMethod(array(
                'name' => 'getDbTable',
                'body' => '
                    if (null === $this->_dbTable) {
                        $a = get_class($this);
                        $a = substr( str_replace("_Model_", "_Model_DbTable_", $a),0,-6);
                        $this->setDbTable($a);
                    }
                    return $this->_dbTable;
                ',
                'docblock' => new Zend_CodeGenerator_Php_Docblock(array(
                    //'shortDescription' => 'Set the ' . lcfirst($propertyName) . ' property',
                    'tags' => array(
                        new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
                            'paramName' => 'value',
                            'datatype' => 'string'
                        )),
                        new Zend_CodeGenerator_Php_Docblock_Tag_Return(array(
                            'datatype' => $modelClassName
                        ))
                    )
                ))
            ));
        
        $file = new Zend_CodeGenerator_Php_File(array(
            'classes' => array($class)
        ));
        return $file->generate();
        /*
        $name = "MapperAbstract";
        $filename = $this->_options['mapper']['path'] . DIRECTORY_SEPARATOR . $name . '.php';
        echo $filename;
        file_put_contents($filename, $file->generate());
        */
    }

}