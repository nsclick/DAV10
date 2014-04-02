<?php
/**
 * @version		$Id: helper.php 2010-07-26 sgarcia $
 * @package		Joomla
 * @subpackage	DO
 * @autor		Diseño Objetivo www.do.cl
 * @copyright	Copyright (C) 2006 - 2010 Diseño Objetivo. Todos los derechos reservados.
 * @license		LICENCIA_DO.php
 */

	/**************************************/
	/*                                    */
	/*          Diseño Objetivo           */
	/*       www.do.cl / info@do.cl       */
	/*      Fono: (56-02) 228 13 91       */
	/*                                    */
	/**************************************/
	
// No se puede acceder directamente
defined('_JEXEC') or die('Restricted access');

class modChatHelper
{
	function getDatos( &$params )
	{
		JPluginHelper::importPlugin('content');
		$dispatcher				=& JDispatcher::getInstance();
		$doc					=& JFactory::getDocument();
		$user					=& JFactory::getUser();
		$db						= JFactory::getDBO();
					
		$datos					= new stdClass;
		$datos->online			= $user->get('id') ? true : false;
		
		$query = "SELECT DISTINCT u.id, u.username, u.name  " .
		"FROM #__users u, #__session s ".
		"WHERE u.id = s.userid ".
		"AND u.gid = 18 ".
		"AND u.id != ". (int) $user->get('id')." ".
		"ORDER BY u.name";
		$db->setQuery($query);
		$datos->usuarios		= $db->loadObjectList();
		
		if( $datos->online ) :
			// se agregan los scripts necesarios
			JHTML::_('behavior.mootools');
			$doc->addScript( 'modules/mod_chat/mod_chat.js' );
		endif;
		
		return $datos;
	}	
}
