<?php
class Preceptor_Generator_Adapter_Oracle extends Preceptor_Generator_Adapter_Abstract
{
    public static function getReference($schema, $table)
    {
	$relations = array();
        $sql = 'SELECT '
             . 'rcc.table_name AS referenced_table_name, '
             . 'lcc.column_name AS local_column_name, '
             . 'rcc.column_name AS referenced_column_name '
             . 'FROM user_constraints ac '
             . 'JOIN user_cons_columns rcc ON ac.r_constraint_name = rcc.constraint_name '
             . 'JOIN user_cons_columns lcc ON ac.constraint_name = lcc.constraint_name '
             . "WHERE ac.constraint_type = 'R' AND ac.table_name = ?";

        $results = $this->_db->fetchAll($sql, $table);
        foreach ($results as $result) {
            $className = self::camelizes($result['referenced_table_name']);
            $result = array_change_key_case($result, CASE_LOWER);
            $relations[$className] = array(
                'refTableClass' => $result['referenced_table_name'],
                'columns'       => $result['local_column_name'],
                'refColumns'    => $result['referenced_column_name']
            );
        }
        return $relations;
    }

    public static function getPrimaryKey($table)
    {
        //$db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $metadata = $this->_db->describeTable($table);
        $primary = array();
        foreach ($metadata as $col)
        {
            if ($col['PRIMARY']) {
                $primary[$col['PRIMARY_POSITION']] = $col['COLUMN_NAME'];
            }
        }
        return $primary;
    }

    public static function listTables()
    {
        //$db = Zend_Db_Table_Abstract::getDefaultAdapter();
        return $this->_db->fetchCol("SELECT TABLE_NAME FROM user_tables");
    }

    public static function getDependent($schema, $table)
    {
        $sql = "SELECT TABLE_NAME
                FROM user_constraints
                WHERE R_CONSTRAINT_NAME IN
                ( SELECT CONSTRAINT_NAME
                FROM user_constraints
                WHERE
                OWNER = '$schema' AND
                TABLE_NAME = '$table'  )
                AND STATUS = 'ENABLED'
                ";
                //echo $sql;
                //exit;
        //$results = $db->fetchAll($sql, array('SIOUV',$table));
        $results = $this->_db->fetchAll($sql);
        $relations = array();
        foreach ($results as $result) {
            //$result = array_change_key_case($result, CASE_LOWER);
            $relations[] = $result['TABLE_NAME'];

        }
        return $relations;
    }
}
