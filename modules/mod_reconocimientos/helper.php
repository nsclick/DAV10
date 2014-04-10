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

class modReconocimientosHelper
{
	function getDatos( &$params )
	{

		$datos					= new stdClass;
		$datos->titulo			= $params->get('titulo','');
		$datos->subtitulo		= $params->get('subtitulo','');
		$datos->menu			= $params->get('menu');
		
		$menu 					= JTable::getInstance('Menu');
		$menu->load( $params->get('menu') );
		$datos->link			= JRoute::_( $menu->link."&Itemid=$menu->id" );
		
		$reconocimientos		=& JTable::getInstance('reconocimientos', 'DO');
		$filtro					= new stdClass;
		$filtro->limit			= 2;
		$rows					= $reconocimientos->lista( $filtro );
		
		$datos->rows			= $rows;
		return $datos;
	}	
}
