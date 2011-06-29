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
 * @category   Application
 * @package    Application_Model
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Application_Model_Content
{
    /**
     * Fetch all content and organize
     *
     * @param integer $disciplineId
     * @param integer $contentId
     * @param array $data
     * @param integer $level
     * @return array
     */
    public static function fetchAllOrganize($courseId, $contentId = null, $data = null, $level = 0)
    {
        $table  = new Tri_Db_Table('content');
        $select = $table->select()
                        ->from('content', array("id", "title"))
                        ->where("course_id = ?", $courseId)
                        ->order(array("position", "id"));

        if ($contentId) {
            $select->where('content_id = ?', $contentId);
        } else {
            $select->where('content_id IS NULL');
        }
        
        $rowset = $table->fetchAll($select)->toArray();

        if (count($rowset)) {
            foreach ($rowset as $row) {
                $row['level'] = $level;
                $data[] = $row;
                $data = self::fetchAllOrganize($courseId, $row['id'], $data, $level + 1);
            }
        }

        return $data;
    }

    /**
     * Create initial content
     *
     * @param integer $courseId
     */
    public static function createInitialContent($courseId)
    {
        $data = array();
        $data['course_id']   = $courseId;
        $data['title']       = "Introdução";
        $data['description'] = "Bem vindo ao curso!";

        $table = new Tri_Db_Table('content');
        $table->createRow($data)->save();
    }

    public static function getLastAccess($classroomId, $data)
    {
        $access = new Zend_Db_Table('content_access');
        $select = $access->select()->where('classroom_id = ?', $classroomId)
                         ->order('content_access.id DESC');
        $row = $access->fetchRow($select);

        if ($row) {
            return self::getPositionById($row->content_id, $data);
        }
        return 0;
    }

    /**
     * Get a position by id
     *
     * @param integer $id
     * @param array $data
     * @return boolean
     */
    public static function getPositionById($id, $data)
    {
		foreach($data as $key => $val) {
			if ($val['id'] == $id) {
				return $key;
			}
		}
		return 0;
	}
}