<?php
class Content_FileController extends Tri_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->view->title = "Database file";
    }
    
    public function indexAction()
    {
        $this->view->removePagePlugin = true;
        $session = new Zend_Session_Namespace('data');
        $table   = new Tri_Db_Table('content_file');
        $page    = Zend_Filter::filterStatic($this->_getParam('page'), 'int');
        $query   = Zend_Filter::filterStatic($this->_getParam("q"), 'stripTags');
        $folder  = Zend_Filter::filterStatic($this->_getParam("folder"), 'stripTags');
        $message = $this->_hasParam('message');
        
        $CKEditorFuncNum = Zend_Filter::filterStatic($this->_getParam('CKEditorFuncNum'), 'int');
        if ($CKEditorFuncNum) {
            $session->CKEditorFuncNum = $CKEditorFuncNum;
        }
        
        if ($message) {
            $this->view->messages = array('Success');
            $this->getResponse()->prepend('messages', $this->view->render('message.phtml'));
        }
        
        if ($folder || $query) {
            $select  = $table->select();
            
            if ($query) {
                $select->where('UPPER(name) LIKE UPPER(?)', "%$query%");
            }
            
            if ($folder) {
                if ($folder === "null") {
                    $select->where("folder IS NULL OR folder = ''");
                } else {
                    $select->where('folder = ?', $folder);
                }
            }

            $paginator = new Tri_Paginator($select, $page);
            $this->view->data = $paginator->getResult();
            $this->view->q = $query;
            $this->view->folder = $folder;
            $this->view->CKEditorFuncNum = $session->CKEditorFuncNum;
            
            $this->render('file');
        } else {
            $select = $table->select(true)->group('folder')->order('folder');
            $this->view->data = $table->fetchAll($select);
            
            $this->render('folder');
        }
    }

    public function formAction()
    {
        $form = new Content_Form_File();
        $this->view->form = $form;
    }

    public function saveAction()
    {
        $form  = new Content_Form_File();
        $table = new Tri_Db_Table('content_file');
        $data  = $this->_getAllParams();

        if ($form->isValid($data)) {
            if (!$form->location->receive()) {
                $this->_helper->_flashMessenger->addMessage('File fail');
            }
            
            $data = $form->getValues();
            
            if (isset($data['folder']) && !$data['folder']) {
                unset($data['folder']);
            }
            
            $data['user_id'] = Zend_Auth::getInstance()->getIdentity()->id;

            $row = $table->createRow($data);
            $id = $row->save();
            
            $this->view->folder = 'null';
            
            if (isset($data['folder']) && $data['folder']) {
                $this->view->folder = $data['folder'];
            }
        } else {
            $this->_response->prepend('messages', $this->view->translate('Error'));
            $this->view->form = $form;
            $this->render('form');
        }
    }

    public function deleteAction()
    {
        $table    = new Tri_Db_Table('content_file');
        $id       = Zend_Filter::filterStatic($this->_getParam('id'), 'int');
        $location = $this->_getParam('location');
        $path     = APPLICATION_PATH . '/../data/upload/' . $location;
        
        if (file_exists($path)) {
            @unlink($path);
        }

        if ($id) {
            $table->delete(array('id = ?' => $id));
            $this->_helper->_flashMessenger->addMessage('Success');
        }

        $this->_redirect('content/file/index/');
    }
}