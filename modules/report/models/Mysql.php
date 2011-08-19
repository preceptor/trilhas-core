<?php
class Report_Model_Mysql
{
    public static function getReference($schema, $table)
    {
        $data   = array();
        $db     = Zend_Db_Table_Abstract::getDefaultAdapter();
        $result = $db->fetchRow( "SHOW CREATE TABLE `$table`" );
        $sql    = $result["Create Table"];
        $values = explode("CONSTRAINT", $sql);

        if (count($values) > 1) {
            for ($i = 1; $i < count($values); $i++) {
                $foreign       = explode("FOREIGN KEY", $values[$i]);
                $foreignData   = explode("`", $foreign[1]);
                $references    = explode("REFERENCES", $foreign[1]);
                $referenceData = explode("`", $references[1]);

                $data[] = array('reference_schema' => null,
                                'reference_table'  => $referenceData[1],
                                'reference_column' => $referenceData[3],
                                'column'           => $foreignData[1]);
            }
        }
        return $data;
    }

    public static function getDependent($schema, $table, $field = 'id')
    {
        $data   = array();
        $db     = Zend_Db_Table_Abstract::getDefaultAdapter();
        $tables = $db->listTables();
        
        foreach($tables as $value) {
            $result = $db->fetchRow("SHOW CREATE TABLE `$value`");
            $sql    = @$result["Create Table"];
            $values = explode("CONSTRAINT", $sql);

            if (count($values) > 1) {
                for ($i = 1; $i < count($values); $i++) {
                    $foreign       = explode("FOREIGN KEY", $values[$i]);
                    $foreignData   = explode("`", $foreign[1]);
                    $references    = explode("REFERENCES", $foreign[1]);
                    $referenceData = explode("`", $references[1]);

                    if ($referenceData[1] == $table && $referenceData[3] == $field) {
                        $data[] = array('table'  => $value,
                                        'column' => $foreignData[1],
                                        'schema' => null);
                    }
                }
            }
        }
        return $data;
    }
}