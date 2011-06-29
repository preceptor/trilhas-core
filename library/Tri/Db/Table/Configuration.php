<?php
class Tri_Db_Table_Configuration extends Tri_Db_Table
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
    protected $_name = 'configuration';

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
    protected $_referenceMap = array();
}