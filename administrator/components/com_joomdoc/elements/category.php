<?php
/**
 * JoomDOC - Joomla! Document Manager
 * @version $Id: category.php 1 2009-09-01 11:34:40Z j.trumpes $
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

require_once (JPATH_BASE . DS . 'components' . DS . 'com_joomdoc' . DS . 'elements' . DS . 'helper.php');

class JElementCategory extends JElement
{
    public $_name = 'Document';
    
    function fetchElement ($name, $value, &$node, $control_name)
    {
        $db = &JFactory::getDBO();
        $category = new mosDMCategory($db);
        if ($value) {
            $category->load($value);
        } else {
            $category->title = JText::_('Select an Category');
        }
        return JElementJoomdocHelper::fetchElement($name, $value, $control_name, $category, 'categories', 'Category');
    }
}
?>