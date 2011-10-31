<?php
class Migration_Model_ContentTest extends PHPUnit_Framework_TestCase
{
    public function testInitialization()
    {
        $this->assertInstanceOf('Migration_Model_Content', new Migration_Model_Content());
    }
    
    public function testExport()
    {
        $courseId = 1;
        $model    = new Migration_Model_Content();
        $zip      = $model->export($courseId);
        
        $this->assertInstanceOf("ZipArchive", $zip);
    }
    
    public function testImport()
    {
        $courseId = 2; 
        $model    = new Migration_Model_Content();
        $model->import($courseId, APPLICATION_PATH . '/../data/content1.zip');
    }
    
    public static function tearDownAfterClass() 
    {
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    }
}