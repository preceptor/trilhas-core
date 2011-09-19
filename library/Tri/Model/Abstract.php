<?php
/**
 * Trilhas - Learning Management System
 * Copyright (C) 2005-2011  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
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
 * @package    Tri_Model
 * @copyright  Copyright (C) 2005-2011  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Tri_Model_Abstract implements Tri_Model_Interface
{
    /**
     * Errors messages
     * @var array
     */
    protected $_messages = array();
    
    /**
     * Set messages
     * 
     * @param array $messages
     * @return void
     */
    public function setMessages($messages) 
    {
        $this->_messages = $messages;
    }
    
    /**
     * Add message
     * 
     * @param string $message
     * @return void
     */
    public function addMessage($message) 
    {
        $this->_messages[] = $message;
    }
    
    /**
     * Get all message
     * 
     * @return array
     */
    public function getMessages() 
    {
        return $this->_messages;
    }
}
