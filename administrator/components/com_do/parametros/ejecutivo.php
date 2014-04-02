<?php
/**
 * @version		$Id: controlador.php 14401 2010-06-03 sgarcia $
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
class JElementEjecutivo extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Ejecutivo';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$db = &JFactory::getDBO();
		
		$query = "SELECT u.id, u.name"
		. " FROM #__users AS u"
		. " WHERE u.gid = 19"
		. " ORDER BY u.name ASC"
		;
		$db->setQuery( $query );
		$users	= $db->loadObjectList();
		
		$opciones		= array();
		
		foreach( $users as $u ) :
			$opciones[]	= JHTML::_('select.option', $u->id, $u->name, 'id', 'name');
		endforeach;

		array_unshift($opciones, JHTML::_('select.option', '', JText::_('- Seleccionar -'), 'id', 'name'));

		return JHTML::_('select.genericlist',  $opciones, ''.$control_name.'['.$name.']', 'class="inputbox"', 'id', 'name', $value, $control_name.$name );
	}
}
