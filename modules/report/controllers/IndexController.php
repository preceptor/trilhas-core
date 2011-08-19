<?php
/**
 * Trilhas - Learning Management System
 * Copyright (C) 2005-2011  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
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
 * @package    Report_Controller
 * @copyright  Copyright (C) 2005-2011 Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Report_IndexController extends Zend_Controller_Action 
{
    protected $_report;

    public function init() 
    {
        parent::init();
        $this->_report = new Report_Model_Builder();
        
        $this->view->title = 'Report';
        $this->view->headLink()->appendStylesheet('css/report/main.css')
                               ->appendStylesheet('css/report/style.css');
        $this->view->headScript()->appendFile('js/report.js');
    }

    public function indexAction() 
    {
        $this->_report->clear();
        $this->view->tables = $this->_report->listTables();
    }

    public function fieldAction() 
    {
        $this->_report->clear();

        $schema = $this->_getParam('schema');
        $table = $this->_getParam('table');

        $this->view->fields = $this->_report->getColunms($schema, $table);
        $this->view->tables = $this->_report->getRelationTables($schema, $table);

        $this->_helper->layout->disableLayout();
    }

    public function moreAction() 
    {
        $info   = Zend_Json::decode($this->_getParam('info'));
        $schema = $info['schema'];
        $table  = $info['table'];

        $this->_report->addJoin($info);

        $this->view->fields = $this->_report->getColunms($schema, $table);
        $this->view->tables = $this->_report->getRelationTables($schema, $table);

        $this->_helper->layout->disableLayout();
    }

    public function listAction() 
    {
        $info = Zend_Json::decode($this->_getParam('info'));

        if ($info) {
            $this->_report->add($info);
        }

        $this->_setViewFields();
        $this->_helper->layout->disableLayout();
    }

    public function orderAction() 
    {
        $colunm    = $this->_getParam('colunm');
        $direction = $this->_getParam('direction');
        $add       = $this->_getParam('add');

        $this->_report->addOrder($colunm, $direction, $add);

        $this->_setViewFields();

        $this->_helper->layout->disableLayout();
        $this->render('list');
    }

    public function filterAction() 
    {
        $colunm   = $this->_getParam('colunm');
        $value    = $this->_getParam('value');
        $operator = $this->_getParam('operator');
        $logic    = $this->_getParam('logic');
        $isExpr   = $this->_getParam('isExpr');

        $this->_report->addFilter($colunm, $value, $operator, $logic, $isExpr);
        $this->_setViewFields();

        $this->_helper->layout->disableLayout();
        $this->render('list');
    }

    public function aggregateAction() 
    {
        $this->_report->addAggregate($this->_getParam('colunm'));
        $this->_setViewFields();

        $this->_helper->layout->disableLayout();
        $this->render('list');
    }

    public function columnAction() 
    {
        $this->_report->addColumn($this->_getParam('colunm'));
        $this->_setViewFields();

        $this->_helper->layout->disableLayout();
        $this->render('list');
    }

    protected function _setViewFields() 
    {
        $page = Zend_Filter::filterStatic($this->_getParam('page'), 'int');

        $this->view->rs         = $this->_report->fetchAll($page);
        $this->view->allColunms = $this->_report->getAllColunms();
        $this->view->colunms    = $this->_report->getSelectedColunms();
        $this->view->orders     = $this->_report->getOrders();
        $this->view->filters    = $this->_report->getFilters();
        $this->view->aggregates = $this->_report->getAggregates();
    }
}