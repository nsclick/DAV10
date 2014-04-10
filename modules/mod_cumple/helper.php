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

class modCumpleHelper
{
	function getDatos( &$params )
	{
		$doc					=& JFactory::getDocument();

		ob_start();
		$datos					= new stdClass;
		$datos->titulo			= $params->get('titulo','');
		$datos->subtitulo		= $params->get('subtitulo','');
		$datos->menu			= $params->get('menu');
		$oracle					=& JTable::getInstance('oracle', 'DO');
		ob_end_clean();
		
		$datos->fecha			= date("d").' de '.fixMes(ucfirst(strftime("%B"))).' del '.date("Y");
		
		$menu 					= JTable::getInstance('Menu');
		$menu->load( $params->get('menu') );
		$datos->link			= JRoute::_( $menu->link."&Itemid=$menu->id" );
		
		$filtro					= new stdClass;
		$filtro->cumplemes		= date('m');
		
		/**
		 * This was removed by NSClick upon some improvements on load time required.
		 * Instead of loading birthdays everytime homepage loads, it is read from a 
		 * local file in json format, which is updated by a cron that runs per day. 
		 *
		 * Author:  Luis Carlos Osorio Jayk
		 * Company: NSCLICK
		 * Date: 	02-04-2014
		 */
		// if( !$rows				= $oracle->personas( $filtro ) ) {
		// 	$datos->error		= $oracle->_error;
		// } else {
		// 	$datos->rows		= $rows;
		// };
		
		$json_file_content 	= file_get_contents ( JURI::base() . 'modules/mod_cumple/birthdays.json' );
		$datos->rows 		= json_decode( $json_file_content, true );
		
		return $datos;
	}	
}
