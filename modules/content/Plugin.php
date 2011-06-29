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
 * @package    Content_Plugin
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Content_Plugin extends Tri_Plugin_Abstract
{
    protected $_name = "content";

    protected function _createDb()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `content` (
                  `id` bigint(20) NOT NULL AUTO_INCREMENT,
                  `course_id` bigint(20) NOT NULL,
                  `content_id` bigint(20) DEFAULT NULL,
                  `title` varchar(255) NOT NULL,
                  `description` text,
                  `position` int(10) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`id`),
                  KEY `course_id` (`course_id`),
                  KEY `content_id` (`content_id`)
                );

                CREATE TABLE IF NOT EXISTS `content_access` (
                  `id` bigint(20) NOT NULL AUTO_INCREMENT,
                  `content_id` bigint(20) NOT NULL,
                  `user_id` bigint(20) NOT NULL,
                  `classroom_id` bigint(20) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `content_id` (`content_id`),
                  KEY `user_id` (`user_id`)
                );

                CREATE TABLE IF NOT EXISTS `content_file` (
                  `id` bigint(20) NOT NULL AUTO_INCREMENT,
                  `user_id` bigint(20) NOT NULL,
                  `name` varchar(255) NOT NULL,
                  `location` varchar(255) NOT NULL,
                  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`),
                  KEY `user_id` (`user_id`)
                );

                CREATE TABLE IF NOT EXISTS `content_template` (
                  `id` bigint(20) NOT NULL AUTO_INCREMENT,
                  `name` varchar(255) NOT NULL,
                  `description` text NOT NULL,
                  PRIMARY KEY (`id`)
                )";

        $this->_getDb()->query($sql);
    }

    public function install()
    {
        $this->_createDb();
    }

    public function activate()
    {
        $this->_addClassroomMenuItem('creation','content','content/composer/index');
        $this->_addClassroomMenuItem('creation','organizer','content/organizer/index');
        $this->_addClassroomMenuItem('creation','database-file','content/file/index');
        $this->_addClassroomMenuItem('creation','template','content/template/index');
        $this->_addClassroomMenuItem('creation','restriction','content/restriction/index');
        //$this->_addClassroomMenuItem('support','print','content/print/index');

        $this->_addAclItem('content/index/view', 'identified');
        $this->_addAclItem('content/print/index', 'identified');
        $this->_addAclItem('content/print/view', 'identified');
        $this->_addAclItem('content/composer/index', 'teacher, coordinator, institution');
        $this->_addAclItem('content/composer/form', 'teacher, coordinator, institution');
        $this->_addAclItem('content/composer/save', 'teacher, coordinator, institution');
        $this->_addAclItem('content/composer/delete', 'teacher, coordinator, institution');
        $this->_addAclItem('content/organizer/index', 'teacher, coordinator, institution');
        $this->_addAclItem('content/organizer/save', 'teacher, coordinator, institution');
        $this->_addAclItem('content/restriction/time-form', 'teacher, coordinator, institution');
        $this->_addAclItem('content/restriction/time-delete', 'teacher, coordinator, institution');
        $this->_addAclItem('content/restriction/time-save', 'teacher, coordinator, institution');
        $this->_addAclItem('content/restriction/panel-form', 'teacher, coordinator, institution');
        $this->_addAclItem('content/restriction/panel-delete', 'teacher, coordinator, institution');
        $this->_addAclItem('content/restriction/panel-save', 'teacher, coordinator, institution');
        $this->_addAclItem('content/restriction/index', 'teacher, coordinator, institution');
        $this->_addAclItem('content/file/form', 'teacher, coordinator, institution');
        $this->_addAclItem('content/file/delete', 'teacher, coordinator, institution');
        $this->_addAclItem('content/file/save', 'teacher, coordinator, institution');
        $this->_addAclItem('content/file/index', 'teacher, coordinator, institution');
        $this->_addAclItem('content/file/download', 'teacher, coordinator, institution');
        $this->_addAclItem('content/template/form', 'teacher, coordinator, institution');
        $this->_addAclItem('content/template/delete', 'teacher, coordinator, institution');
        $this->_addAclItem('content/template/save', 'teacher, coordinator, institution');
        $this->_addAclItem('content/template/index', 'teacher, coordinator, institution');
    }

    public function desactivate()
    {
        $this->_removeClassroomMenuItem('creation','content');
        $this->_removeClassroomMenuItem('creation','organizer');
        $this->_removeClassroomMenuItem('creation','restriction');
        $this->_removeClassroomMenuItem('creation','database-file');
        $this->_removeClassroomMenuItem('creation','template');
        $this->_removeClassroomMenuItem('support','print');

        $this->_removeAclItem('content/index/view');
        $this->_removeAclItem('content/print/index');
        $this->_removeAclItem('content/print/view');
        $this->_removeAclItem('content/composer/index');
        $this->_removeAclItem('content/composer/form');
        $this->_removeAclItem('content/composer/save');
        $this->_removeAclItem('content/composer/delete');
        $this->_removeAclItem('content/organizer/index');
        $this->_removeAclItem('content/organizer/save');
        $this->_removeAclItem('content/restriction/time-form');
        $this->_removeAclItem('content/restriction/time-delete');
        $this->_removeAclItem('content/restriction/time-save');
        $this->_removeAclItem('content/restriction/panel-form');
        $this->_removeAclItem('content/restriction/panel-delete');
        $this->_removeAclItem('content/restriction/panel-save');
        $this->_removeAclItem('content/restriction/index');
        $this->_removeAclItem('content/file/form');
        $this->_removeAclItem('content/file/delete');
        $this->_removeAclItem('content/file/save');
        $this->_removeAclItem('content/file/index');
        $this->_removeAclItem('content/file/download');
        $this->_removeAclItem('content/template/form');
        $this->_removeAclItem('content/template/delete');
        $this->_removeAclItem('content/template/save');
        $this->_removeAclItem('content/template/index');
    }
}