<?php
class Preceptor_Generator
{
    private $_adapter = null;
    /*
    protected $_options  = array('path'         => '',
                                'model'        => array('prefix'  => 'Default_Model_',
                                                        'extends' => 'Default_Model_Abstract'),
                                'mapper'       => array('prefix'  => 'Default_Model_',
                                                        'extends' => 'Default_Model_Abstract'),
                                'table'        => array('prefix'  => 'Default_Model_DbTable_',
                                                        'extends' => 'Default_Model_DbTable_Abstract'),
                                'controller'   => array('extends' => 'Default_Controller_Action_Abstract'));
    */
    
   protected $basePath = '';


   protected $_options = array(
        'path' => array(
            'model'  => 'Model',
            'table'  => 'DbTable',
            'mapper' => 'Mapper',
        ),
        'prefix' => array(
            'model'  => 'Model',
            'table'  => 'DbTable',
            'mapper' => 'Mapper',
        ),
    );
   
   protected $objetos = array(
        'model'  => null,
        'table'  => null,
        'mapper' => null,
    );

    public function  __construct(array $options = array())
    {
        $this->_options = array_merge($this->_options, $options);
        $this->basePath = APPLICATION_PATH . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'library';
        $this->createPaths();
    }
    
    public function generate($itens = array('model','table','mapper')) 
    {
        $generatedAbstract = array(
            'model'  => false,
            'mapper' => false,
            'table'  => false,
        );
        
        $tables = $this->getAdapter()->listTables();
        
        foreach ($tables as $tableName) {
            $table = new Zend_Db_Table($tableName);
            try{
                $metadata   = $this->getAdapter()->describeTable($tableName);
                $primary    = $this->getAdapter()->getPrimaryKey($tableName);
                $relations  = $this->getAdapter()->getReference ('TRA', $tableName);
                $dependents = $this->getAdapter()->getDependent ('TRA', $tableName);
                
                foreach($itens as $item)
                {
                    $path =  APPLICATION_PATH . '/../library/' . $this->_options['path'][$item];
                    $name = $this->camelize(strtolower($tableName));
                    $filename = $path . DIRECTORY_SEPARATOR . $name . '.php';
                    $className = $this->_options['prefix'][$item] . '_' . $name;
                    $modelName = $this->_options['prefix']['model'] . '_' . $name;
        
                    $class = 'Preceptor_Generator_Php_' . ucfirst($item);
                    
                    if (!$this->objetos[$item]) {
                        $this->objetos[$item] = new $class();
                    } 
                    
                    $objeto = $this->objetos[$item];
                    
                    switch ($item) {
                        case 'model': 
                            $file = $objeto->_create($className, $tableName, $metadata, $primary, $relations, $dependents, 'Model_Abstract');
                            break;
                        case 'table': 
                            $file = $objeto->_create($className, $tableName, $primary, $relations, $dependents);
                            break;
                        case 'mapper': 
                            $file = $objeto->_create($className, $modelName, $tableName, $metadata, $primary, $relations, $dependents, 'Mapper_Abstract');
                            break;
                    }
                    
                    //$file = $objeto->_create();
                    if (!file_put_contents($filename, $file)) {
                        throw new Exception ('Erro ao gravar o arquivo');
                    }
                    
                    if (!$generatedAbstract[$item]) {
                        if (method_exists($this->objetos[$item], 'generateAbstract')) {
                            $nameAbstract = 'Abstract';
                            $fileNameAbstract = $path . DIRECTORY_SEPARATOR . $nameAbstract . '.php';
                            $classNameAbstract = $this->_options['prefix'][$item] . '_' . $nameAbstract;
                            
                            $file = call_user_func(array($this->objetos[$item], 'generateAbstract'),$classNameAbstract); 
                            if (!file_put_contents($fileNameAbstract, $file)) {
                                throw new Exception('Erro ao gravar o arquivo');
                            }
                        }
                        
                        $generatedAbstract[$item] = true;
                    }
                    
                }
            } catch (Exception $e) {
                throw $e;
            }
        }    
    }

    public function createPaths()
    {
        foreach ($this->_options['path'] as $path)
        {
            $dir = $this->basePath . DIRECTORY_SEPARATOR . $path;
            if (!file_exists($dir)) {
                //exec("mkdir {dir}");
                mkdir ($dir, 0777 );
            }
        
            //exec("chmod -R 777 {$dir}");
        }
    }
    
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
        } 
        return $this->_adapter;
    }
    
    public function camelize($value) {
        return str_replace(" ", "", ucwords(str_replace("_", " ", strtolower($value))));
    }

}