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
 * @category   Admin
 * @package    Admin_Controller
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Admin_ThemeController extends Tri_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->_helper->layout->setLayout('admin');
        $this->view->title = 'Theme';
        $this->themesPath = APPLICATION_PATH . '/../themes/';
    }
    
    public function indexAction()
    {
        $iterator = new DirectoryIterator($this->themesPath);
        $this->view->data = $iterator;
        $this->view->installed = Tri_Config::get('tri_themes_installed', true);
    }

    public function activateAction()
    {
        $name     = $this->_getParam('name');
        $oldTheme = new Tri_Theme(Tri_Config::get('tri_theme'));
        $theme    = new Tri_Theme($name);

        $oldTheme->desactivate();
        $theme->activate();

        $this->_helper->flashMessenger->addMessage('Success');

        $this->_redirect('admin/theme/index');
    }
}