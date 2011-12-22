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
 * @category   Content
 * @package    Content_Model
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Content_Model_Print 
{
    /**
     * Fetch all content and organize
     *
     * @param integer $disciplineId
     * @param integer $contentId
     * @param array $data
     * @return array
     */
    public static function fetchOrganizeWithContent($courseId, $contentId = null, $data = null)
    {
        $table  = new Tri_Db_Table('content');
        if (!$data && $contentId) {
            $data[] = $table->find($contentId)->current()->toArray();
        }
        $select = $table->select()
                        ->from('content', array("id", "title", "description"))
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
                $data[] = $row;
                $data = self::fetchOrganizeWithContent($courseId, $row['id'], $data);
            }
        }
        

        return $data;
    }
}