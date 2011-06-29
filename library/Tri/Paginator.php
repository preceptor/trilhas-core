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
 * @package    Tri_Paginator
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Tri_Paginator extends Zend_Paginator
{
    /**
     * Query will be paged.
     *
     * @var Zend_Db_Select or Zend_Db_Table_Select
     */
    protected $_select;

    /**
     * Page current.
     *
     * @var integer
     */
    protected $_page;

    /**
     * Items per page.
     *
     * @var integer
     */
    protected $_quantity;

    /**
     * (non-PHPdoc)
     * @see Zend_Paginator#__construct()
     */
    public function __construct($select, $page, $quantity = 10)
    {
        $this->_select   = $select;
        $this->_page     = $page;
        $this->_quantity = $quantity;
    }

    /**
     * @return Zend_Paginator
     */
    public function getResult()
    {
        $paginator = Zend_Paginator::factory($this->_select);
        $paginator->setCurrentPageNumber($this->_page);
        $paginator->setItemCountPerPage($this->_quantity);
        $paginator->setDefaultScrollingStyle('Sliding');

        Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');

        return $paginator;
    }
}
