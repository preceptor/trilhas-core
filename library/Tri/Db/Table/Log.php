<?php
class Tri_Db_Table_Log extends Tri_Db_Table
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
    protected $_name = 'log';

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
    protected $_dependentTables = array();

    /**
     * Reference tables
     *
     * var array
     */
    protected $_referenceMap = array(
        array('refTableClass' => 'Tri_Db_Table_User',
              'refColumns'    => array('id'),
              'columns'       => array('user_id')),
        array('refTableClass' => 'Tri_Db_Table_Classroom',
              'refColumns'    => array('id'),
              'columns'       => array('classroom_id'))
    );
}