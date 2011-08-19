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
class Application_Model_Timeline
{
    /**
     * Get all timeline registers by user registry classroom
     *
     * @param array $courses
     * @param int $page
     * @return Zend_Db_Rowset
     */
    public static function getByClassroom($courses, $limit, $page)
    {
        if ($courses) {
            foreach ($courses as $course) {
                $ids[] = $course['classroom_id'];
            }
        } else {
            $ids = array(0);
        }

        $table = new Tri_Db_Table('timeline');
        $select = $table->select(true)
                        ->setIntegrityCheck(false)
                        ->join('user', 'user.id = user_id', array('user.id as uid','user.name','user.image','user.role'))
                        ->join('classroom', 'classroom.id = classroom_id', array())
                        ->join('course', 'course.id = course_id', array('course.name as cname'))
                        ->where('classroom_id IN(?)', $ids)
                        ->order('id DESC')
                        ->limit($limit, $page-1);
        
        return $table->fetchAll($select);
    }
	
	/**
	 * Save timeline
	 *
	 * @param string $description 
	 * @param string $postInfo 
	 * @return void
	 */
    public static function save($description, $postInfo = null)
    {
        $timeline  = new Tri_Db_Table('timeline');
        $session   = new Zend_Session_Namespace('data');
        $translate = Zend_Registry::get('Zend_Translate');
        $userId    = Zend_Auth::getInstance()->getIdentity()->id;

        if ($postInfo) {
            $postInfo = ' - ' . $postInfo;
        }
        
        if (isset($session->classroom_id) && $userId) {
            $data = array('user_id' => $userId,
                          'classroom_id' => $session->classroom_id,
                          'description' => $translate->_($description) . $postInfo);
            $timeline->createRow($data)->save();
        }
    }
}