<?php
/**
 * ZFDebug Zend Additions
 *
 * @category   ZFDebug
 * @package    ZFDebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2008-2009 ZF Debug Bar Team (http://code.google.com/p/zfdebug)
 * @license    http://code.google.com/p/zfdebug/wiki/License     New BSD License
 * @version    $Id: Session.php 113 2009-06-23 14:34:03Z abtris $
 */
/**
 * @category   ZFDebug
 * @package    ZFDebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2008-2009 ZF Debug Bar Team (http://code.google.com/p/zfdebug)
 * @license    http://code.google.com/p/zfdebug/wiki/License     New BSD License
 */
class ZFDebug_Controller_Plugin_Debug_Plugin_Session extends ZFDebug_Controller_Plugin_Debug_Plugin implements ZFDebug_Controller_Plugin_Debug_Plugin_Interface
{

    /**
     * Contains plugin identifier name
     *
     * @var string
     */
    protected $_identifier = 'session';
    /**
     * Create ZFDebug_Controller_Plugin_Debug_Plugin_Variables
     *
     * @param Zend_Db_Adapter_Abstract|array $adapters
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Gets identifier for this plugin
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->_identifier;
    }

    /**
     * Gets menu tab for the Debugbar
     *
     * @return string
     */
    public function getTab()
    {

        $html = "Session";
        return $html;
    }

    /**
     * Gets content panel for the Debugbar
     *
     * @return string
     */
    public function getPanel()
    {
        $html = '<h4>Session</h4>';
        $html .= '<pre>';
        if (isset($_SESSION)) {
            $html .= var_export($_SESSION, true);
        }
        $html .= '</pre>';

        return $html;
    }

}