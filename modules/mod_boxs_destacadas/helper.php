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

class modBoxsDestacadasHelper
{
	function getDatos( &$params )
	{
		$datos					= new stdClass;
		$datos->titulo			= $params->get('titulo','');
		$datos->descripcion		= $params->get('autoconsulta') && !IPprivada() ? nl2br($params->get('autoconsulta_offline','')) : nl2br($params->get('descripcion',''));
		
		$menu 					= JTable::getInstance('Menu');
		$menu->load( $params->get('menu') );
		$datos->link			= $menu->type == 'url' || $menu->type == 'menulink' ? JRoute::_( $menu->link ) : JRoute::_( $menu->link."&Itemid=$menu->id" );
		$datos->browserNav		= $menu->browserNav;
		
		if( $params->get('icono','') ) :
			$icono				= JPATH_BASE.DS.'images'.DS.'boxs'.DS.$params->get('icono','');
			if( file_exists( $icono ) ) :
				$datos->icono	= '<img src="'.JURI::base().'images/boxs/'.$params->get('icono','').'" alt="'.$datos->titulo.'" title="'.$datos->titulo.'" border="0" />';
			endif;
		endif;
		
		return $datos;
	}	
}
