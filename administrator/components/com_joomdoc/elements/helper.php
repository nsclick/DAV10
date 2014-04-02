<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: helper.php 1 2009-09-01 11:34:40Z j.trumpes $
 * @package JoomDOC
 * @copyright (C) 2009 Artio s.r.o.
 * @license see COPYRIGHT.php
 * @link http://www.artio.net Official website
 * JoomDOC is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 **/
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_BASE . DS . 'components' . DS . 'com_joomdoc' . DS . 'docman.class.php');

class JElementJoomdocHelper
{
    function fetchElement ($name, $value, $control_name, $row, $section, $title)
    {
        JHTML::_('behavior.modal', 'a.modal');
        
        $fieldName = $control_name . '[' . $name . ']';
        
        $doc = & JFactory::getDocument();
        $doc->addScript(JURI::root() . 'administrator/components/com_joomdoc/includes/js/docmanjavascript.js');
        
        $link = 'index.php?option=com_joomdoc&section=' . $section . '&task=element&tmpl=component&object=' . $name;
        
        $html = "\n" . '<div style="float: left;"><input style="background: #ffffff;" type="text" id="' . $name . '_name" value="' . htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8') . '" disabled="disabled" /></div>';
        $html .= '<div class="button2-left"><div class="blank"><a class="modal" title="' . JText::_('Select an ' . $title) . '"  href="' . $link . '" rel="{handler: \'iframe\', size: {x: 800, y: 600}}">' . JText::_('Select') . '</a></div></div>' . "\n";
        $html .= '<div class="button2-left"><div class="blank"><a title="' . JText::_('Clear') . '" href="#" onclick="MM_resetElement(\''.$name.'\')">' . JText::_('Clear') . '</a></div></div>' . "\n";
        $html .= "\n" . '<input type="hidden" id="' . $name . '_id" name="' . $fieldName . '" value="' . (int) $value . '" />';
        
        return $html;
    }
}
?>