<?php
class Admin_Model_UserTest extends PHPUnit_Framework_TestCase
{
    public function testInitialization()
    {
        $this->assertInstanceOf('Admin_Model_User', new Admin_Model_User());
    }
    
    public function testSave()
    {
        $model = new Admin_Model_User();
        $data = array('name' => 'Test user',
                      'email' => 'test@user.com',
                      'password' => '123',
                      'password_confirm' => '123',
                      'sex' => 'M',
                      'born' => '11/03/1986',
                      'role' => 'student',
                      'description' => 'asdfasdf',
                      'status' => 'active');
        
        $this->assertTrue((boolean) $model->save($data));
        
        $dataTest = $data;
        $dataTest['id'] = 1;
        unset($dataTest['password'], $dataTest['password_confirm']);
        $this->assertTrue((boolean) $model->save($dataTest), $this->messageToString($model->getMessages()));
        
        $dataTest = $data;
        $dataTest['name'] = '';
        $this->assertFalse($model->save($dataTest), $this->messageToString($model->getMessages()));
        
        $dataTest = $data;
        $dataTest['password_confirm'] = '1234';
        $this->assertFalse($model->save($dataTest), $this->messageToString($model->getMessages()));
        
        $dataTest = $data;
        $dataTest['email'] = 'testuser';
        $this->assertFalse($model->save($dataTest), $this->messageToString($model->getMessages()));
        
        $dataTest = $data;
        $dataTest['role'] = 'admin';
        $this->assertFalse($model->save($dataTest), $this->messageToString($model->getMessages()));
        
        $dataTest = $data;
        $dataTest['sex'] = 'm';
        $this->assertFalse($model->save($dataTest), $this->messageToString($model->getMessages()));
        
        $dataTest = $data;
        $dataTest['status'] = 'waiting';
        $this->assertFalse($model->save($dataTest), $this->messageToString($model->getMessages()));
        
        $dataTest = $data;
        $dataTest['born'] = '13/13/1986';
        $this->assertFalse($model->save($dataTest), $this->messageToString($model->getMessages()));
    }
    
    public function testFindById()
    {
        $model = new Admin_Model_User();
        $data = $model->findById(1);
        
        $this->assertInternalType('array', $data);
        $this->assertInternalType('array', $model->findById(array(1)));
        
        $this->assertArrayHasKey('id', $data);
    }
    
    public function testFindNameOrEmail()
    {
        $model = new Admin_Model_User();

        $data = $model->findByNameOrEmail('test');
        $this->assertEquals(1, count($data));
        
        $data = $model->findByNameOrEmail('test@user.com');
        $this->assertEquals(1, count($data));
    }
    
    public static function tearDownAfterClass() 
    {
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $db->query('SET foreign_key_checks=0; TRUNCATE user');
    }
    
    public function messageToString($messages) 
    {
        $stringMessage = '';
        $errors        = current($messages);
        
        if (is_array($errors) && count($errors)) {
            foreach ($errors as $field => $error) {
                foreach ($error as $key => $message) {
                    $stringMessage .= "\n" . $field . ' => ' . '[' . $key . '] ' . $message; 
                }
            }
        }
        
        return $stringMessage;
    }
}