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
 * @category   Application
 * @package    Application_Controller
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class ThemeController extends Tri_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->view->title = "Theme";
        $this->_helper->layout->setLayout('admin');
    }

    public function indexAction()
    {
        $form = new Application_Form_Theme();
        
        $form->populate(array('style' => Tri_Config::get('tri_theme_style'),
                              'custom_css' => Tri_Config::get('tri_custom_css')));
        
        $this->view->form = $form;
        $this->view->logo = Tri_Config::get('tri_logo');
    }

    public function saveAction()
    {
        $form  = new Application_Form_Theme();
        $data  = $this->_getAllParams();

        if ($form->isValid($data)) {
            if (!$form->logo->receive()) {
                $messages[] = 'Image fail';
            }
            $data = $form->getValues();
            
            if ($data['style']) {
                Tri_Config::set('tri_theme_style', $data['style']);
            }

            Tri_Config::set('tri_custom_css', $data['custom_css']);
            
            if ($form->logo->getValue()) {
                Tri_Config::set('tri_logo', $data['logo']);
            }
            
            $this->_helper->flashMessenger->addMessage('Success');
            $this->_redirect('default/theme/index');
        }
        
        $form->populate($data);
        $this->view->form = $form;
        $this->view->logo = Tri_Config::get('tri_logo');
        
        $this->render('index');
    }
}
