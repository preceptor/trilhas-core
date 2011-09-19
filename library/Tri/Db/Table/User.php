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
 * @category   Tri
 * @package    Tri_Db
 * @copyright  Copyright (C) 2005-2011  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Tri_Db_Table_User extends Tri_Db_Table
{
    /**
     * Table name
     *
     * var string
     */
    protected $_name = 'user';

    /**
     * Table primary keys
     *
     * var array
     */
    protected $_primary = array('id');

    /**
     * Dependent tables
     *
     * var array
     */
    protected $_dependentTables = array('Tri_Db_Table_Classroom', 'Tri_Db_Table_ClassroomUser', 'Tri_Db_Table_Course', 'Tri_Db_Table_Log', 'Tri_Db_Table_Timeline');

    /**
     * Reference tables
     *
     * var array
     */
    protected $_referenceMap = array();
    
    public function getFilters() 
    {
        $filters = parent::getFilters();
        
        $filters['name'][] = 'StripTags';
        $filters['description'][] = 'StripTags';
        
        return $filters;
    }
    
    public function getValidators() 
    {
        $validators = parent::getValidators();
        $validators['email'][] = 'EmailAddress';
        $validators['password'][] = array('Identical', 
                                          false, 
                                          array('token' => 'password_confirm'));
        $validators['role'][] = array('InArray', 
                                      false, 
                                      array('haystack' => array('student', 
                                                                'teacher', 
                                                                'coordinator', 
                                                                'institution')));
        $validators['sex'][] = array('InArray', 
                                     false, 
                                     array('haystack' => array('M', 'F')));
        $validators['status'][] = array('InArray', 
                                        false, 
                                        array('haystack' => array('active', 'inactive')));
        return $validators;
    }
}