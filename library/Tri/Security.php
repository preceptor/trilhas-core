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
 * @package    Tri_Security
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Tri_Security
{
    /**
     * Check if user can access a course
     *
     * @param integer $userId
     * @param integer $courseId
     * @return boolean
     */
    public static function course($courseId)
    {
        $table = new Zend_Db_Table('course');
        $select = $table->select()->where('course.id = ?', $courseId);
        $course = $table->fetchRow($select);

        if (!$course) {
            throw new Tri_Security_Exception('Invalid access. Course.');
        }
        
        return true;
    }

    /**
     * Check if user can access a classroom
     *
     * @param integer $userId
     * @param integer $classroomId
     * @return boolean
     */
    public static function classroom($classroomId, $userId = null)
    {
        $table = new Zend_Db_Table('classroom');
        $select = $table->select(true)->setIntegrityCheck(false)
                        ->join('course', 'course.id = classroom.course_id', array())
                        ->where('classroom.id = ?', $classroomId);
        $classroom = $table->fetchRow($select);

        if ($userId) {
            $table = new Zend_Db_Table('classroom_user');
            $classroomUser = $table->fetchRow(array('user_id = ?' => $userId,
                                                    'classroom_id = ?' => $classroomId));
        }
        
        if (!$classroom) {
            throw new Tri_Security_Exception('Invalid access. Classroom.');
        }
        
        if (isset($classroomUser) && !$classroomUser) {
            throw new Tri_Security_Exception('Invalid access. Classroom User.');
        }

        return true;
    }

    /**
     * Check if user can access a user
     *
     * @param integer $userId
     * @param integer $userCheckId
     * @return boolean
     */
    public static function user($userId)
    {
        $table = new Zend_Db_Table('user');
        $user = $table->fetchRow(array('id = ?' => $userId));
        
        if (!$user) {
            throw new Tri_Security_Exception('Invalid access. User');
        } 
        
        return true;
    }
}
