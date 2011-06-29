<?php
abstract class Preceptor_Generator_Php_Abstract {
    protected $_options = array();
    private $_adapter = null;
    
    
    
    public function camelize($value) {
        return str_replace(" ", "", ucwords(str_replace("_", " ", strtolower($value))));
    }

    public function createPaths() {
        $paths = explode(DIRECTORY_SEPARATOR, $this->_options['path']);
        $current_path = null;

        array_shift($paths);

        foreach ($paths as $path) {
            $current_path .= DIRECTORY_SEPARATOR . $path;
            if (!file_exists($current_path)) {
                mkdir($current_path);
                exec("chmod 777 {$current_path}");
            }
        }
    }

    /*
    public function listTables()
    {
        $adapter = $this->getAdapterName();
        return $adapter::listTables();
    }

    public function getPrimaryKey($table)
    {
        $adapter = $this->getAdapterName();
        return $adapter::getPrimaryKey($table);
    }

    public function getReference($schema, $table)
    {
        $adapter = $this->getAdapterName();
        return $adapter::getReference($schema, $table);
       
    }

    public function getDependent($schema, $table, $field = 'id')
    {
        $adapter = $this->getAdapterName();
        return $adapter::getDependent($schema, $table, $field);
    }
    */
    public function getAdapterName()
    {
        $className = get_class(Zend_Db_Table::getDefaultAdapter());
        switch ($className)
        {
            case 'Zend_Db_Adapter_Pdo_Oci'   : $adapter = 'Oracle'; break;
            //case 'Zend_Db_Adapter_Pdo_Mysql' : $adapter = 'Mysql'; break;
            case 'Zend_Db_Adapter_Pdo_Pgsql' : $adapter = 'Pgsql'; break;
            default: $adapter = 'Mysql'; break;
        }
        //$adapter = str_replace('Zend_Db_Adapter_Pdo_Oci_', 'Preceptor_Generator_Adapter_', $className);

        $adapter = 'Preceptor_Generator_Adapter_' . $adapter;
        return $adapter;
    }
    
    public function getAdapter() 
    {
        if (!$this->_adapter) {
            $db      = Zend_Db_Table_Abstract::getDefaultAdapter();
            $adapter = $this->getAdapterName();
            $this->_adapter = new $adapter($db);
        } else {
            return $this->_adapter;
        }
    }
}