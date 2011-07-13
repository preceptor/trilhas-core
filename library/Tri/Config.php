<?php
/**
 * Trilhas - Learning Management System
 * Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
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
 * @package    Tri_Config
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Tri_Config
{
    /**
     *
     * @var array
     */
    public static $_data;

    /**
     * Get an option by name
     *
     * @param string $name
     */
    public static function get($name, $decoded = false)
    {
        if (isset(self::$_data[$name])) {
            if ($decoded) {
                return Zend_Json::decode(self::$_data[$name]);
            }
            return self::$_data[$name];
        }

        $table = new Tri_Db_Table('configuration');
        $where = array('name = ?' => $name);

        $row = $table->fetchRow($where);

        if ($row) {
            self::$_data[$name] = $row->value;
            if ($decoded) {
                return Zend_Json::decode($row->value);
            }
            return $row->value;
        }

        throw new Tri_Exception('Invalid option.' . $name);
    }

    /**
     * Get all options
     *
     * @param boolean $decoded
     * @param boolean $autoload
     */
    public static function getAll($decoded = false, $autoload = false)
    {
        if ($autoload) {
            $where = array('autoload = ?' => $autoload);
        }

        $table   = new Tri_Db_Table('configuration');
        $data    = $table->fetchAll($where);
        $options = array();
        
        if (count($data)) {
            foreach ($data as $row) {
                $name = str_replace('tri_', '', $row->name);
                if ($decoded) {
                    $options[$name] = Zend_Json::decode($row->value);
                } else {
                    $options[$name] = $row->value;
                }
            }
        }
        
        return $options;
    }

    public static function set($name, $data, $encoded = false)
    {
        $table = new Tri_Db_Table('configuration');
        $where = array('name = ?' => $name);

        $row = $table->fetchRow($where);

        if (!$row) {
            $row = $table->createRow();
            $row->name = $name;
        }

        if ($encoded) {
            $data = Zend_Json::encode($data);
        }
        
        self::$_data[$name] = $row->value = $data;
        $row->save();

        return $row->value;
    }
}