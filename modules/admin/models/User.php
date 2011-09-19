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
 * @category   Admin
 * @package    Admin_Controller
 * @copyright  Copyright (C) 2005-2011 Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Admin_Model_User extends Tri_Model_Abstract
{
    /**
     * Find users by name or email
     * 
     * @param string $value
     * @param integer $page
     * @return Zend_Paginator
     */
    public function findByNameOrEmail($value, $page = 1) 
    {
        $page   = Zend_Filter::filterStatic($page, 'int');
        $table  = new Tri_Db_Table_User();
        $select = $table->select()->order('name');

        if ($value) {
            $select->where('(name LIKE ?', "%$value%");
            $select->orWhere('email LIKE ?)', "%$value%");
        }

        $paginator = new Tri_Paginator($select, $page);
        return $paginator->getResult();
    }
    
    /**
     * Find user by id
     * 
     * @param integer $id
     * @return array 
     */
    public function findById($id)
    {
        $table = new Tri_Db_Table_User();
        $row   = $table->find($id)->current();
        
        if ($row) {
            return $row->toArray();
        }
        
        return array();
    }
    
    public function save($data) 
    {
        $table      = new Tri_Db_Table_User();
        $validators = $table->getValidators();
        
        if (isset($data['password_confirm'])) {
            array_pop($validators['password']);
            $validators['password'][] = array('Identical', false, array('token' => $data['password_confirm']));
        }
        
        $input = new Tri_Filter_Input($table->getFilters(), $validators, $data);
        
        if ($input->isValid()) {
            if ($data['email'] && (!isset($data['id']) || !$data['id'])) {
                $row = $table->fetchRow(array('email = ?' => $data['email']));
                if ($row) {
                    $this->addMessage('Email existing');
                    return false;
                }
            }

            if (isset($data['image']) && !$data['image']) {
                unset($data['image']);
            }

            if (isset($data['password']) && !$data['password']) {
                unset($data['password']);
            } 
            
            if (isset($data['password']) && $data['password']) {
                $salt = Tri_Config::get('tri_salt');
                $data['password'] = md5($salt . $data['password']);
            }
            
            unset($data['password_confirm']);

            if (isset($data['id']) && $data['id']) {
                $table->update($data, array('id = ?' => $data['id']));
                return $data['id'];
            } 
            
            return $table->insert($data);
        }
        
        $this->setMessages(current($input->getMessages()));
        return false;
    }
}
