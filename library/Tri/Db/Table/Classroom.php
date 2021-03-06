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
class Tri_Db_Table_Classroom extends Tri_Db_Table
{
    /**
     * Table name
     *
     * var string
     */
    protected $_name = 'classroom';

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
    protected $_dependentTables = array('Tri_Db_Table_ClassroomUser', 
                                        'Tri_Db_Table_Log', 
                                        'Tri_Db_Table_Timeline');

    /**
     * Reference tables
     *
     * var array
     */
    protected $_referenceMap = array(
        array('refTableClass' => 'Tri_Db_Table_Course',
              'refColumns'    => array('id'),
              'columns'       => array('course_id')),
        array('refTableClass' => 'Tri_Db_Table_User',
              'refColumns'    => array('id'),
              'columns'       => array('responsible'))
    );
}