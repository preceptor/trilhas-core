<?php
class Tri_Db_Table_User extends Tri_Db_Table
{
    /**
     * Schema name
     *
     * var string
     */
    protected $_schema = '';

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
}