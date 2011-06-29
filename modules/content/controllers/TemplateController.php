<?php
class Content_TemplateController extends Tri_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->view->title = "Template";
    }
    
    public function indexAction()
    {
        $table   = new Tri_Db_Table('content_template');
        $page    = Zend_Filter::filterStatic($this->_getParam('page'), 'int');
        $query   = $this->_getParam("q");
        $select  = $table->select();

        if ($query) {
            $select->where('UPPER(name) LIKE UPPER(?)', "%$query%");
        }
        
        $paginator = new Tri_Paginator($select, $page);
        $this->view->data = $paginator->getResult();
        $this->view->q = $query;
    }

    public function formAction()
    {
        $id   = Zend_Filter::filterStatic($this->_getParam('id'), 'int');
        $form = new Content_Form_Template();

        if ($id) {
            $table = new Tri_Db_Table('content_template');
            $row   = $table->find($id)->current();

            if ($row) {
                $form->populate($row->toArray());
            }
        }

        $this->view->form = $form;
    }

    public function saveAction()
    {
        $form    = new Content_Form_Template();
        $table   = new Tri_Db_Table('content_template');
        $data    = $this->_getAllParams();

        if ($form->isValid($data)) {
            $data = $form->getValues();

            if (isset($data['id']) && $data['id']) {
                $row = $table->find($data['id'])->current();
                $row->setFromArray($data);
                $id = $row->save();
            } else {
                unset($data['id']);
                $row = $table->createRow($data);
                $id = $row->save();
            }

            $this->_helper->_flashMessenger->addMessage('Success');
            $this->_redirect('content/template/form/id/'.$id);
        }

        $this->view->messages = array('Error');
        $this->view->form = $form;
        $this->render('form');
    }

    public function deleteAction()
    {
        $table = new Tri_Db_Table('content_template');
        $id    = Zend_Filter::filterStatic($this->_getParam('id'), 'int');

        if ($id) {
            $table->delete(array('id = ?' => $id));
            $this->_helper->_flashMessenger->addMessage('Success');
        }

        $this->_redirect('content/template/index/');
    }
}