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
 * @package    Tri_Filter
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Tri_Filter_Date implements Zend_Filter_Interface
{
    /**
     * Set options
     * @var array
     */
    protected $_options = array(
        'locale'      => null,
        'date_format' => null,
        'precision'   => null
    );

    /**
     * Class constructor
     *
     * @param array|Zend_Config $options (Optional)
     */
    public function __construct($options = null)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }

        if (null === $options) {
            $locale = key(Zend_Registry::get('Zend_Locale')->getDefault());
            $dateFormat = Zend_Locale_Data::getContent($locale, 'date');

            $options = array('locale' => $locale, 'date_format' => $dateFormat);
        }
        
        $this->setOptions($options);
    }

    /**
     * Returns the set options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Sets options to use
     *
     * @param  array $options (Optional) Options to use
     * @return Tri_Filter_Date
     */
    public function setOptions(array $options = null)
    {
        $this->_options = $options + $this->_options;
        return $this;
    }

    /**
     * Filter date
     *
     * @param string $value
     * @return null|string
     */
    public function filter($value)
    {
        if ($value) {
            $date = new Zend_Date($value, null, $this->_options['locale']);
            if (Zend_Date::isDate($value, $this->_options['date_format'], $this->_options['locale'])) {
                return $date->toString('yyyy-MM-dd');
            } else {
                return $date->toString($this->_options['date_format']);
            }
        }
        return null;
    }
}
