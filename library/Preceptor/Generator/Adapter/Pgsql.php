<?php
class Preceptor_Generator_Adapter_Pgsql
{
    public static function getReference($schema, $table)
    {
        $sql = "SELECT a.attname as \"column\", nf.nspname as reference_schema, clf.relname as reference_table, af.attname as \"reference_column\"
                                FROM pg_catalog.pg_attribute a
                                JOIN pg_class cl ON (a.attrelid = cl.oid AND cl.relkind = 'r')
                                JOIN pg_namespace n ON (n.oid = cl.relnamespace)
                                JOIN pg_constraint ct ON (a.attrelid = ct.conrelid AND ct.confrelid != 0 AND ct.conkey[1] = a.attnum)
                                JOIN pg_class clf ON (ct.confrelid = clf.oid AND clf.relkind = 'r')
                                JOIN pg_namespace nf ON (nf.oid = clf.relnamespace)
                                JOIN pg_namespace nfi ON (nfi.oid = cl.relnamespace)
                                JOIN pg_attribute af ON (af.attrelid = ct.confrelid AND af.attnum = ct.confkey[1])
                                WHERE  cl.relname = '$table' AND n.nspname = '$schema'
                                ORDER BY a.attname";

        $result = $this->_db->fetchAll($sql);
        return $result;
    }

    public function getPrimaryKey($table)
    {
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
        return $this->_db->listTables();
    }

    public static function getDependent($schema, $table, $field = 'id')
    {
        $sql = "SELECT a.attname as \"column\",
                        n.nspname as \"schema\",
                        cl.relname as \"table\"
                FROM pg_catalog.pg_attribute a
                JOIN pg_class cl ON (a.attrelid = cl.oid AND cl.relkind = 'r')
                JOIN pg_namespace n ON (n.oid = cl.relnamespace)
                JOIN pg_constraint ct ON (a.attrelid = ct.conrelid AND ct.confrelid != 0 AND ct.conkey[1] = a.attnum)
                JOIN pg_class clf ON (ct.confrelid = clf.oid AND clf.relkind = 'r')
                JOIN pg_namespace nf ON (nf.oid = clf.relnamespace)
                JOIN pg_namespace nfi ON (nfi.oid = cl.relnamespace)
                JOIN pg_attribute af ON (af.attrelid = ct.confrelid AND af.attnum = ct.confkey[1])
                WHERE clf.relname = '$table' AND nf.nspname = '$schema' AND af.attname = '$field'
                ORDER BY cl.relname";

        $result = $this->_db->fetchAll($sql);
        return $result;
    }
}