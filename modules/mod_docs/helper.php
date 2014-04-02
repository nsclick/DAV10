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

class modDocsHelper
{
	function getDatos( &$params )
	{
		$user					=& JFactory::getUser();
		$db						=& JFactory::getDBO();
		
		$datos					= new stdClass;
		$datos->titulo			= $params->get('titulo','');
		$datos->subtitulo		= $params->get('subtitulo','');
		$datos->menu			= $params->get('menu');
		
		$menu 					= JTable::getInstance('Menu');
		$menu->load( $datos->menu );
		$datos->link			= JRoute::_( $menu->link."&Itemid=$menu->id" );
		
		require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomdoc'.DS.'docman.class.php' );
		require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomdoc'.DS.'helpers'.DS.'factory.php' );
		require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomdoc'.DS.'classes'.DS.'DOCMAN_utils.class.php' );
		$datos->rows			= DOCMAN_Docs::getDocsByUserAccessHome ( 0, 'date', 'DESC', 5, 0);
		
		if( count( $datos->rows ) ) :
			foreach( $datos->rows as $i => $row ) :
				$datos->rows[$i]->link	= DOCMAN_Utils::taskLink('doc_download', $row->id, array('Itemid'=>$menu->id) );
			endforeach;
		endif;
		
		return $datos;
	}	
}
