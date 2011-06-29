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
class Application_Model_Classroom
{
	/**
     * Status type: waiting
     */
	const REGISTERED = 'registered';
		
    /**
     * Get all possible classroom
     *
     * @param int $userId
     * @return array
     */
    public static function getAllByUser($userId)
    {
        $session   = new Zend_Session_Namespace('data');
        $db        = Zend_Db_Table::getDefaultAdapter();
        $cols      = array('cr.*', 'c.*', 'c.id as id',
                           'cr.id as classroom_id',
                           'cr.name as classroom_name');
        $course    = array('c' => 'course');
        $classroom = array('cr' => 'classroom');
        $classUser = array('cu' => 'classroom_user');
        $data      = array();
        
        //by course or classroom responsible
        $select = $db->select()
                     ->from($classroom, $cols)
                     ->join($course, 'c.id = cr.course_id', array())
                     ->where('c.responsible = ? OR cr.responsible = ?', $userId)
                     ->where("cr.status = 'active' OR cr.status = 'open'")
                     ->where("c.status = 'active'")
                     ->order('cr.begin');
        $responsibles = $db->fetchAll($select);

        //by registration
        $select = $db->select()
                     ->from($classroom, $cols)
                     ->join($course, 'c.id = cr.course_id', array())
                     ->join($classUser, 'cr.id = cu.classroom_id', array())
                     ->where('cu.user_id = ?', $userId)
                     ->where('cu.status = ?', 'registered')
                     ->where('cr.end >= ? OR end IS NULL', date('Y-m-d'))
                     ->where("cr.status = 'active' OR cr.status = 'open'")
                     ->where("c.status = 'active'")
                     ->order('cr.begin');
        $registries = $db->fetchAll($select);

        foreach ($responsibles as $responsible) {
            $data[] = $responsible;
            $session->classrooms[] = $responsible['classroom_id'];
        }

        foreach ($registries as $registry) {
            if (in_array($registry, $data)) {
                continue;
            }
            $data[] = $registry;
            $session->classrooms[] = $registry['classroom_id'];
        }
        
        return $data;
    }

    /**
     * Get all possible classroom
     *
     * @param int $userId
     * @return array
     */
    public static function getFinalizedByUser($userId)
    {
        $certificate = new Tri_Db_Table('certificate');
        $select = $certificate->select(true)->setIntegrityCheck(false)
                              ->join('classroom', 'classroom.id = certificate.classroom_id', array())
                              ->join('course', 'course.id = classroom.course_id')
                              ->where('certificate.user_id = ?', $userId);

        return $certificate->fetchAll($select);
    }
	
	/**
	 * Verify if class it's available
	 *
	 * @param int $id 
	 * @return boolean
	 */
    public static function isAvailable($id)
    {
        $classroom     = new Tri_Db_Table('classroom');
        $classroomUser = new Tri_Db_Table('classroom_user');

        $row = $classroom->fetchRow(array('id = ?' => $id));

        if (!$row || $row->visibility == 'private' || $row->status != 'open') {
            return false;
        }

        $select = $classroomUser->select(true)
                                ->columns('COUNT(0) as total')
                                ->where('classroom_id = ?', $id);

        $total = $classroom->fetchRow($select)->total;

        if ($row->max_student > 0 && $row->max_student <= $total) {
            return false;
        }

        return true;
    }

	/**
	 * Get all class it's available
	 *
	 * @param int $id
	 * @return object select
	 */
    public static function getAvailable($id)
    {
		$table     = new Tri_Db_Table('selection_process_classroom');
        $classroom = new Tri_Db_Table('classroom');
		$selectIn  = $table->select()
                           ->from(array('p' => 'selection_process_classroom'), array('p.classroom_id'))
                           ->where('selection_process_id = ?', $id);
        $select = $classroom->select(true)
                            ->setIntegrityCheck(false)
                            ->join('course', 'course.id = course_id', array())
                            ->where('course.status = ?', 'active')
                            ->where('classroom.status = ?', 'active')
							->where('classroom.id not in (?)', $selectIn);
        return $select;
    }

    public static function accessed($classroomId, $userId = null)
    {
        $table   = new Tri_Db_Table('content_access');
        $content = new Tri_Db_Table('content');

        $select = $table->select(true)
                        ->columns('COUNT(0) as total')
                        ->where('user_id = ?', $userId)
                        ->where('classroom_id = ?', $classroomId)
                        ->group('content_id');
        $accessed = $table->fetchRow($select);
        
        $select = $content->select(true)->setIntegrityCheck(false)
                          ->columns('COUNT(0) as total')
                          ->join('classroom', 'content.course_id = classroom.course_id')
                          ->where('classroom.id = ?', $classroomId);
        $total = $content->fetchRow($select);

        if ($total && $accessed) {
            if ($accessed->total >= $total->total) {
                return 100;
            }

            return ceil(($accessed->total*100)/$total->total);
        }
        
        return 0;
    }
}