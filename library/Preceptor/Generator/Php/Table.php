<?php
class Preceptor_Generator_Php_Table extends Preceptor_Generator_Php_Abstract{

    public function _create($className, $tableName, $primary, $relations, $dependents)
    {
        $class = new Zend_CodeGenerator_Php_Class();

        $docblock = new Zend_CodeGenerator_Php_Docblock(array(
            'shortDescription' => 'Automatically generated data model',
            'longDescription'  => 'This class has been automatically generated based on the dbTable "' . $tableName . '" @ ' . strftime('%d-%m-%Y %H:%M')
        ));

        $class->setName($className)
            ->setDocblock($docblock)
            //->setExtendedClass('Plano_Model_Abstract')
            // Property
            ->setProperty(array(
                //'name' => lcfirst($propertyName),
                'name' => '_name',
                'visibility' => 'protected',
                'defaultValue' => $tableName,
            ))
            ->setProperty(array(
                //'name' => lcfirst($propertyName),
                'name' => '_primary',
                'visibility' => 'protected',
                'defaultValue' => $primary,
            ))
            ->setProperty(array(
                //'name' => lcfirst($propertyName),
                'name' => '_dependentTables',
                'visibility' => 'protected',
                'defaultValue' => $dependents,
            ))
            ->setProperty(array(
                //'name' => lcfirst($propertyName),
                'name' => '_referenceMap',
                'visibility' => 'protected',
                'defaultValue' => $relations,
            ));

        $file = new Zend_CodeGenerator_Php_File(array(
            'classes' => array($class)
        ));

        return $file->generate();
    }
}