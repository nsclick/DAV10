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
class JElementControlador extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Controlador';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$db = &JFactory::getDBO();
		
		jimport('joomla.filesystem.folder');
		$controladores 	= JFolder::files( JPATH_ROOT.DS.'components'.DS.'com_do'.DS.'controladores', '.php' );
		
		$opciones		= array();
		
		foreach( $controladores as $c ) :
			$c	= substr( $c, 0, -4 );
			$opciones[]	= JHTML::_('select.option', $c, ucfirst($c), 'c', 'nombre');
		endforeach;

		array_unshift($opciones, JHTML::_('select.option', '', JText::_('- Por Defecto -'), 'c', 'nombre'));

		return JHTML::_('select.genericlist',  $opciones, ''.$control_name.'['.$name.']', 'class="inputbox"', 'c', 'nombre', $value, $control_name.$name );
	}
}
