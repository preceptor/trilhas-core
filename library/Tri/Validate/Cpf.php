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
 * @package    Tri_Validate
 * @copyright  Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 * @license    http://www.gnu.org/licenses/  GNU GPL
 */
class Tri_Validate_Cpf extends Zend_Validate_Abstract
{
    const INVALID = 'isEmpty';

    /**
     * @var string[]
     */
    protected $_messageTemplates = array(
        self::INVALID => "'%value%' does not appear to be a valid CPF"
    );

    /**
     * (non-PHPdoc)
     * @see Zend_Validate_Abstract#isValid()
     */
    public function isValid($value)
    {
        $cpf = (string) $value;
        $this->_setValue($cpf);
        
        if (empty($cpf)) {
            return true;
        }
        
        $sum  = 0;
        $null = array('00000000000', '11111111111', '22222222222', '33333333333',
                      '44444444444', '55555555555', '66666666666', '77777777777',
                      '88888888888', '99999999999');
        if (in_array($cpf, $null) || strlen($cpf) != 11) {
             $this->_error();
            return false;
        }
        
        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $cpf[$i] * (10 - $i);
        }

        $remain = 11 - ($sum % 11);
        if ($remain == 10 || $remain == 11) {
            $remain = 0;
        }

        if ($remain != (int) $cpf[9]) {
            $this->_error();
            return false;
        }

        $sum = 0;

        for ($j = 0; $j < 10; $j++) {
            $sum += (int) $cpf[$j] * (11 - $j);
        }

        $remain = 11 - ($sum % 11);
        if ($remain == 10 || $remain == 11) {
            $remain = 0;
        }
        
        if ($remain != (int)$cpf[10]) {
            $this->_error();
            return false;
        }
        
        return true;
    }
}