<?php
class Report_Model_Builder
{
    protected $_sessionData = null;
    protected $_colunms = null;

    public function  __construct($data = null) 
    {
        if ($data) {
            $this->_sessionData = (object) unserialize($data);
        } else {
            $this->_sessionData = new Zend_Session_Namespace('report');
        }
    }

    public function clear() 
    {
        $this->_sessionData->data       = null;
        $this->_sessionData->join       = null;
        $this->_sessionData->filter     = null;
        $this->_sessionData->order      = null;
        $this->_sessionData->aggregate  = null;
        $this->_sessionData->column     = null;
        $this->_sessionData->allColunms = null;
    }

    public function add(array $info) 
    {
        $this->_sessionData->data[] = $info;
    }

    public function addJoin(array $join) 
    {
        $this->_sessionData->join[] = $join;
    }

    public function addOrder($colunm, $direction, $add = "false") 
    {
        if ($add == "false") {
            $this->_sessionData->order = null;
        }

        $this->_sessionData->order[] = array('colunm' => $colunm,
            'direction' => $direction);
    }

    public function addFilter($colunm, $value, $operator = "=", $logic = null, $isExpr = false) 
    {
        if (!$logic) {
            $this->_sessionData->filter = null;
        }

        $this->_sessionData->filter[] = array('colunm'   => $colunm,
                                              'value'    => $value,
                                              'operator' => $operator,
                                              'logic'    => $logic,
                                              'isExpr'   => $isExpr);
    }

    public function addAggregate($colunm) 
    {
        $this->_sessionData->order = null;
        $this->_sessionData->aggregate[$colunm] = $colunm;
    }

    public function addColumn($colunm) 
    {
        $this->_sessionData->column[$colunm] = $colunm;
    }

    public function remove($index) 
    {
        unset($this->_sessionData->data[$index]);
    }

    public function fetchAll($page = 1, $itemPerPage = 20) 
    {
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $select = $db->select();
        $colunms = array();
        $groupBy = array();

        foreach ($this->_sessionData->data as $key => $data) {
            if ($key == 0) {
                $table = $data['table'];
                $schema = $data['schema'];
            }

            if ( array_key_exists($key.'a', (array)$this->_sessionData->aggregate) ) {
                $colunms[$key.'a'] = 'COUNT('.$data['table'].'.'.$data['colunm'].')';
            } else {
                $groupBy[$key.'a'] = $data['table'].'.'.$data['colunm'];
                $colunms[$key.'a'] = $data['table'].'.'.$data['colunm'];
            }

            $tables[$key.'a'] = $data['table'];
            $schemas[$key.'a'] = $data['schema'];
        }

        if (isset($this->_sessionData->column)) {
            foreach ($this->_sessionData->column as $value) {
                $selectColumn = $db->select();
                $selectColumn->distinct()
                             ->from($tables[$value], $colunms[$value], $schemas[$value])
                             ->order($colunms[$value]);

                $resultColumn = $db->fetchAll($selectColumn);

                foreach ($resultColumn as $key => $val) {
                    $colunms[$value.$key.'b'] = "COUNT(CASE WHEN "
                        . $colunms[$value]."='".current($val)."' "
                        . "THEN 1 END)";
                }

                unset($colunms[$value]);
                unset($groupBy[$value]);
            }
        }

        $this->_colunms = $colunms;

        $select->from($table, $colunms, $schema);

        //Make joins
        if (isset($this->_sessionData->join)) {
            foreach ($this->_sessionData->join as $join) {
                if (isset($join['via'])) {
                    $join['via']['on'] = str_replace('"',"`",$join['via']['on']);
                    $select->join($join['via']['table'],$join['via']['on'], array(), $join['via']['schema']);
                }
                $join['on'] = str_replace('"',"`",$join['on']);
                $select->join($join['table'], $join['on'], array(), $join['schema']);
            }
        }

        //Make orders
        $stringOrder = null;
        if (count($this->_sessionData->order) > 0) {
            $orders = array();
            foreach ($this->_sessionData->order as $order) {
                $colunm    = $order['colunm'];
                $direction = $order['direction'];
                $orders[]  = $colunm.' '.$direction;
            }

            $stringOrder = $orders;
        } else {
            $colunm    = $this->_colunms['0a'];
            $direction = 'ASC';

            $this->_sessionData->order[] = array('colunm' => $colunm,
                                                 'direction' => $direction);

            if (count($this->_sessionData->aggregate) > 0) {
                $stringOrder = $colunm.' '.$direction;
            }
        }

        $select->order($stringOrder);

        //Make filters
        if (count($this->_sessionData->filter) > 0) {
            foreach ($this->_sessionData->filter as $filter) {
                $where = $filter['colunm'] . $filter['operator'] . '?';
                
                if ($filter['isExpr']) {
                    $where = $filter['colunm'] . $filter['operator'] . $filter['value'];
                }
                
                if (strpos($filter['colunm'], 'count') !== false) {
                    if ($filter['logic'] == 'OR') {
                        $select->orHaving($where, $filter['value']);
                    } else {
                        $select->having($where, $filter['value']);
                    }
                } else {
                    if ($filter['logic'] == 'OR') {
                        $select->orWhere($where, $filter['value']);
                    } else {
                        $select->where($where, $filter['value']);
                    }
                }
            }
        }

        //Make groupBy
        if (count($groupBy) > 0) {
            $select->group($groupBy);
        }

        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($itemPerPage);

        Zend_View_Helper_PaginationControl::setDefaultViewPartial('page.phtml');
//        var_dump($select->__toString());
//        debug($db->fetchAll($select));
        return $paginator;
    }

    public function getSelectedColunms() 
    {
        return $this->_colunms;
    }

    public function listTables() 
    {
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $tables = $db->listTables();

        foreach($tables as $key => $value) {
            $table  = new Zend_Db_Table($value);

            try {
                $info = $table->info();
            } catch(Exception $e) {
                continue;
            }

            $countCols = count($info['cols']);
            $countPk   = count($info['primary']);

            if ($countPk == $countCols) {
                continue;
            }
            
            $metadata = current($info['metadata']);
            $countFks = count($this->getReference($metadata['SCHEMA_NAME'],
                                                      $metadata['TABLE_NAME']));
            if ($countFks == $countCols) {
                continue;
            }

            if (($countCols - $countFks) == 1) {
                if (count($countPk) == 1) {
                    continue;
                }
            }
            
            $result[] = array('name'   => $metadata['TABLE_NAME'],
                              'schema' => $metadata['SCHEMA_NAME']);
        }

        return $result;
    }

    public function getTreeColunms($schema,$table) 
    {
        $foreignCols = array();
        $foreignFields = array();
        $result = array();

        $foreigns = $this->getReference($schema, $table);

        foreach($foreigns as $value) {
            $schema = $value['reference_schema'];
            $table = $value['reference_table'];

            $dbTable = new Zend_Db_Table(array('name' => $table, 'schema' => $schema));

            try {
                $info = $dbTable->info();
            } catch(Exception $e) {
                continue;
            }

            $foreignsRel = $this->getReference($schema, $table);

            $selfFields = array_diff($info['cols'], $info['primary']);

            if( count($selfFields) == 0 ) {
                debug(count($selfFields));
            }

            foreach($foreignsRel as $valueRel) {
                $foreignCols[] = $valueRel['column'];
            }

            $fields = array_diff($selfFields, $foreignCols);

            foreach($fields as $field) {
                $colunm = $info['metadata'][$field];
                $result[] = array('colunm' => $colunm['COLUMN_NAME'],
                                  'table'  => $colunm['TABLE_NAME'],
                                  'schema' => $colunm['SCHEMA_NAME']);
            }
        }
        return $result;
    }

    public function getRelationTables($schema, $table) 
    {
        $result = array();
        $via = array();

        $dbTable = new Zend_Db_Table(array('name' => $table, 'schema' => $schema));

        try {
            $info = $dbTable->info();
        } catch(Exception $e) {
            return;
        }

        $field = $info['primary'][1];

        $references = $this->getReference($schema, $table);
        $dependent  = $this->getDependent($schema, $table, $field);

        foreach ($references as $value) {
            $result[] = array('table'  => $value['reference_table'],
                              'schema' => $value['reference_schema'],
                              'on'     => '"'.$table.'"."'.$value['column'].'"'
                                       .  '="'.$value['reference_table'].'"."'.$value['reference_column'].'"',
                              'column' => $value['column']);
        }

        foreach ($dependent as $value) {
            $foreignCols = array();
            $dbTable = new Zend_Db_Table(array('name'   => $value['table'],
                                               'schema' => $value['schema']));
            try {
                $info = $dbTable->info();
            } catch(Exception $e) {
                continue;
            }

            $foreignsRel = $this->getReference($value['schema'], $value['table']);

            foreach ($foreignsRel as $valueRel) {
                $foreignCols[] = $valueRel['column'];
            }

            $selfFields = array_diff($foreignCols, $info['primary']);

            if (count($selfFields) == 0) {
                $references = $this->getReference($value['schema'], $value['table']);

                foreach($references as $val) {
                    if ( $val['reference_table'] != $table) {
                        $via = array('table'  => $value['table'],
                                     'schema' => $value['schema'],
                                     'on'     => '"'.$value['table'].'"."'.$value['column'].'"'
                                              .  '="'.$table.'"."'.$field.'"');

                        $result[] = array('table'  => $val['reference_table'],
                            'schema' => $val['reference_schema'],
                            'on'     => '"'.$value['table'].'"."'.$val['column'].'"'
                                     .  '="'.$val['reference_table'].'"."'.$val['reference_column'].'"',
                            'via'    => $via,
                            'column' => $val['column']);


                    }
                }
            } else {
                $result[] = array('table'  => $value['table'],
                                  'schema' => $value['schema'],
                                  'on'     => '"'.$value['table'].'"."'.$value['column'].'"'
                                           .  '="'.$table.'"."'.$field.'"',
                                  'column' => $value['column']);
            }
        }

        return $result;
    }

    public function getOrders() 
    {
        return $this->_sessionData->order;
    }

    public function getFilters() 
    {
        return $this->_sessionData->filter;
    }

    public function getAggregates() 
    {
        return $this->_sessionData->aggregate;
    }

    public function getColunms($schema, $table) 
    {
        $dbTable = new Zend_Db_Table(array('name' => $table, 'schema' => $schema));
        $result = array();
        $foreignCols = array();

        $info = $dbTable->info();
        $foreigns = $this->getReference($schema, $table);

        foreach($foreigns as $value) {
            $foreignCols[] = $value['column'];
        }

        $selfFields = array_diff($info['cols'], $info['primary']);
        $fields = array_diff($selfFields, $foreignCols);

        foreach($fields as $field) {
            $colunm = $info['metadata'][$field];
            $result[] = array('colunm'=>$colunm['COLUMN_NAME'],
                'table'=>$colunm['TABLE_NAME'],
                'schema'=>$colunm['SCHEMA_NAME']);

            $key = $colunm['TABLE_NAME'] . '.' . $colunm['COLUMN_NAME'];
            
            $this->_sessionData->allColunms[] = $key;
        }


        return $result;
    }

    public function getAllColunms() 
    {
        $colunms    = array_values($this->_colunms);
        $allColunms = array_values($this->_sessionData->allColunms);

        $merged = array_unique(array_merge($colunms, $allColunms));

        sort($merged);

        return $merged;
    }

    public function getReference($schema, $table)
    {
        return Report_Model_Mysql::getReference($schema, $table);
    }

    public function getDependent($schema, $table, $field = 'id')
    {
        return Report_Model_Mysql::getDependent($schema, $table, $field);
    }
}