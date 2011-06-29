<?php
abstract class Preceptor_Generator_Adapter_Abstract 
{
    protected $_db = null;
    
    public function __construct($db)
    {
        $this->_db = $db;
    }
    
    public function camelize($value)
    {
        return str_replace(" ", "", ucwords(str_replace("_", " ", strtolower($value))));
    }
    
    public function describeTable ($table) 
    {
        return $this->_db->describeTable($table);
    }
}