<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: FormRadio.php 20096 2010-01-06 02:05:09Z bkarwin $
 */


/**
 * Abstract class for extension
 */
require_once 'Zend/View/Helper/FormElement.php';


/**
 * Helper to generate a set of radio button elements
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Tri_View_Helper_FormMultiText extends Zend_View_Helper_FormElement
{
    /**
     * Input type to use
     * @var string
     */
    protected $_inputType = 'text';

    /**
     * Whether or not this element represents an array collection by default
     * @var bool
     */
    protected $_isArray = true;

    /**
     * Generates a set of radio button elements.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     *
     * @param mixed $value The radio value to mark as 'checked'.
     *
     * @param array $options An array of key-value pairs where the array
     * key is the radio value, and the array value is the radio text.
     *
     * @param array|string $attribs Attributes added to each radio.
     *
     * @return string The radio buttons XHTML.
     */
    public function formMultiText($name, $value = null, $attribs = null,
        $options = null, $listsep = "<br />\n")
    {
        $info = $this->_getInfo($name, $value, $attribs, $options, $listsep);
        extract($info); // name, value, attribs, options, listsep, disable

        // the radio button values and labels
        $options = (array) $options;
        // build the element
        $xhtml     = '';
        $list      = array();
        $checkedId = null;

        // should the name affect an array collection?
        $name = $this->view->escape($name);
        if ($this->_isArray && ('[]' != substr($name, -2))) {
            $name .= '[]';
        }

        //get cehcked
        if (isset($attribs['checked'])) {
            $checkedId = $attribs['checked'];
            unset($attribs['checked']);
        }
        // ensure value is an array to allow matching multiple times
        $value = (array) $value;

        // XHTML or HTML end tag?
        $endTag = ' />';
        if (($this->view instanceof Zend_View_Abstract) && !$this->view->doctype()->isXhtml()) {
            $endTag= '>';
        }

        // add radio buttons to the list.
        require_once 'Zend/Filter/Alnum.php';
        $filter = new Zend_Filter_Alnum();
        $i = 0;
        foreach ($options as $opt_value => $opt_label) {

            // Should the label be escaped?
            if ($escape) {
                $opt_label = $this->view->escape($opt_label);
            }

            //checked
            $checked = '';
            if ($checkedId === $opt_value) {
                $checked = 'checked="checked"';
            }
        
            // is it disabled?
            $disabled = '';
            if (true === $disable) {
                $disabled = ' disabled="disabled"';
            } elseif (is_array($disable) && in_array($opt_value, $disable)) {
                $disabled = ' disabled="disabled"';
            }

            // generate ID
            $optId = $id . '-' . $filter->filter($opt_value);

            // Wrap the textareas
            $radio = '<div>'
                    . '<input style="float: left;" type="radio" '
                    . ' name="right_' . substr($name, 0, -2) . '"'
                    . ' value="' . $i . '"'
                    . $checked
                    . $endTag
                    . '<textarea '
                    . ' name="' . $name . '"'
                    . $disabled
                    . $this->_htmlAttribs($attribs)
                    . '>'
                    . $this->view->escape($opt_label)
                    . '</textarea>'
                    . '<input type="hidden" '
                    . ' name="id_' . $name . '"'
                    . ' value="' . $this->view->escape($opt_value) . '"'
                    . $endTag
                    . '</div>';

            // add to the array of radio buttons
            $list[] = $radio;
            $i++;
        }

        // done!
        $xhtml .= implode($listsep, $list);

        return $xhtml;
    }
}
