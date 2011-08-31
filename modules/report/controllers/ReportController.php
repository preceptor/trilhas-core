<?php
/**
 * Trilhas - Learning Management System
 * Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * @category   Report
 * @package    Repeort_Controller
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
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