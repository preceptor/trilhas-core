<?php
class Report_View_Helper_Rename extends Zend_View_Helper_Abstract
{
    const CLASSNAME = 'rename_editable';
    
    public function rename($value)
    {
        return '<span class="'.self::CLASSNAME.'">'
               . $this->view->translate(trim($value))
               . '</span>';
    }
}