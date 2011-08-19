<?php
class Report_ReportController extends Zend_Controller_Action 
{
    public function indexAction() 
    {
        $this->_helper->layout->setLayout('admin');
        $report = new Report_Model_DbTable_Report();
        
        $this->view->title = "Report";
        $this->view->data  = $report->fetchAll(null, 'created DESC');
    }

    public function saveAction() 
    {
        $id      = Zend_Filter::filterStatic($this->_getParam('id'), "int");
        $report  = new Report_Model_DbTable_Report();
        $session = new Zend_Session_Namespace('report');

        unset($_POST['id']);
        $row = $report->createRow($_POST);
        $row->data = serialize($_SESSION['report']);

        $row->save();
        
        $this->_helper->_flashMessenger->addMessage('Success');
        $this->_redirect('/report/report');
    }

    public function loadAction() 
    {
        $id   = Zend_Filter::filterStatic($this->_getParam('id'), "int");
        $page = Zend_Filter::filterStatic($this->_getParam( "page" ), "int");

        if (!$id) {
            throw new Exception('Invalid access');
        }

        $report = new Report_Model_DbTable_Report();
        $data   = $report->find($id)->current();

        $builder = new Report_Model_Builder($data->data);

        $this->view->headLink()->appendStylesheet('js/jquery/visualize/jquery.visualize.css')
                               ->appendStylesheet('css/report/main.css')
                               ->appendStylesheet('css/report/style.css');
        $this->view->headScript()->appendFile('js/report.js')
                                 ->appendFile('js/jquery/visualize/jquery.visualize.js')
                                 ->appendFile('js/jquery/visualize/excanvas.compiled.js', null, array('conditional' => 'IE'));
        
        $this->view->title      = "Report";
        $this->view->rs		    = $builder->fetchAll($page);
        $this->view->colunms    = $builder->getSelectedColunms();
        $this->view->orders	    = $builder->getOrders();
        $this->view->filters    = $builder->getFilters();
        $this->view->aggregates = $builder->getAggregates();
    }

    public function deleteAction() 
    {
        $id     = Zend_Filter::filterStatic($this->_getParam('id'), "int");
        $report = new Report_Model_DbTable_Report();

        $report->delete(array('id = ?' => $id));

        $this->_helper->_flashMessenger->addMessage('Success');
        $this->_redirect('/report/report');
    }
}