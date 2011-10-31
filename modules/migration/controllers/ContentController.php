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
 * @category   Migration
 * @package    Migration_Controller
 * @copyright  Copyright (C) 2005-2011  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Migration_ContentController extends Tri_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->_helper->layout->setLayout('admin');
        $this->view->title = 'Migration';
    }
    
    public function indexAction()
    {
        $page  = Zend_Filter::filterStatic($this->_getParam('page'), 'int');
        $table = new Zend_Db_Table('course');
        $select = $table->select()->order('status');

        $paginator = new Tri_Paginator($select, $page);
        $this->view->data = $paginator->getResult();
    }
    
    public function importAction()
    {
        $courseId = $this->_getParam('id');
        $form     = new Migration_Form_Content();
        
        if ($this->_request->isPost()) {
            if ($form->isValid($_POST)) {
                if (!$form->location->receive()) {
                    $this->_helper->_flashMessenger->addMessage('File fail');
                }
                $filename = APPLICATION_PATH . '/../data/upload/' . $form->location->getValue();
                $model = new Migration_Model_Content();
                $model->import($courseId, $filename);

                $this->_helper->flashMessenger->addMessage('Success');
                $this->_redirect('migration/content/index');
            }
        }
        
        $form->id->setValue($courseId);
        $this->view->form = $form;
    }
    
    public function exportAction() 
    {
        $courseId = $this->_getParam('id');
        $model    = new Migration_Model_Content();
        $zip      = $model->export($courseId);
        $fullpath = $model->getFilename($courseId);
        $name     = basename($fullpath);
        
        header("Content-disposition: inline; filename={$name}");
        header("Content-Transfer-Encoding: binary");
        header("Content-type: 'application/zip");
        header("Connection: close");
        
        ob_clean();
        flush();
        readfile($fullpath);
        exit;
    }
}