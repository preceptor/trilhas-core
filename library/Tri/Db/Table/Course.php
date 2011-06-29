<?php
class Tri_Db_Table_Course extends Tri_Db_Table
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
    protected $_name = 'course';

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
    protected $_dependentTables = array('Tri_Db_Table_Classroom');

    /**
     * Reference tables
     *
     * var array
     */
    protected $_referenceMap = array(
        array('refTableClass' => 'Tri_Db_Table_User',
              'refColumns'    => array('id'),
              'columns'       => array('responsible')),
        array('refTableClass' => 'Tri_Db_Table_User',
              'refColumns'    => array('id'),
              'columns'       => array('user_id'))
    );
}