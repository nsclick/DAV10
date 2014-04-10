<?php
/**
 * @version		$Id: seleccionable.php 14401 2010-06-03 sgarcia $
 * @package		Joomla
 * @subpackage	Diseño Objetivo wwww.do.cl
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

/**
 * Muestra un elemento controlador
 *
 * @package		Joomla
 * @subpackage	Diseño Objetivo wwww.do.cl
 * @since		1.5
 */
class JElementSeleccionable extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Seleccionable';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$db = &JFactory::getDBO();
		
		$query = "SELECT s.valor"
		. " FROM #__do_seleccionables AS s"
		. " WHERE s.campo = '".$node->attributes('campo')."'"
		. " ORDER BY s.ordering ASC"
		;
		$db->setQuery( $query );
		$selects	= $db->loadObjectList();
		
		$opciones		= array();
		
		foreach( $selects as $select ) :
			$opciones[]	= JHTML::_('select.option', $select->valor, $select->valor);
		endforeach;

		array_unshift($opciones, JHTML::_('select.option', '', JText::_('- Seleccionar -')));

		return JHTML::_('select.genericlist',  $opciones, ''.$control_name.'['.$name.']', 'class="inputbox"', 'value', 'text', $value, $control_name.$name );
	}
}
