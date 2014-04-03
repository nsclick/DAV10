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

class modPieHelper
{
	function getDatos( &$params )
	{
		JPluginHelper::importPlugin('content');
		$dispatcher				=& JDispatcher::getInstance();
		
		$datos					= new stdClass;
		//$datos->titulo			= $params->get('titulo','');
		$datos->textoCentral	= nl2br($params->get('texto_central',''));
		$datos->textoLateral	= $params->get('texto_lateral_1','');
		$datos->textoLateral	= str_replace( " ", "&nbsp;", $datos->textoLateral );
		$datos->textoLateral	= nl2br($datos->textoLateral);
		
		$menu 					= JTable::getInstance('Menu');
		$menu->load( $params->get('menu') );
		$datos->link			= JRoute::_( $menu->link."&Itemid=$menu->id" );
		$datos->titulo			= $menu->name;
		
		// if( $params->get('modulo',0) ) :
		// 	$datos->modulo		= $params->get('modulo',0);
		// 	$datos->text		= '{loadposition mod_pie}';
		// 	$results			= $dispatcher->trigger('onPrepareContent', array (& $datos, $params, 0));
		// endif;
		
		/**
		 * Fix by NSClick, load of modules in 'mod_pie' position.
		 * Author: 	Luis Carlos Osorio Jayk
		 * Company:	NSCLICK
		 * Date: 	02-04-2014
		 */
		if ( $params->get ( 'modulo', 0 ) ) {
			$document	= &JFactory::getDocument();
			$renderer	= $document->loadRenderer ( 'module' );
			$styles		= array ( 'style' => -2 );

			$contents = '';
			foreach ( JModuleHelper::getModules( 'mod_pie') as $mod )  {
				$contents .= $renderer->render( $mod, $styles );
			}
			
			$datos->modulo 	= $params->get ( 'modulo', 0 );
			$datos->text 	= $contents;
		}

		if( $params->get('icono','') ) :
			$icono				= JPATH_BASE.DS.'images'.DS.'boxs'.DS.$params->get('icono','');
			if( file_exists( $icono ) ) :
				$datos->icono	= '<img src="'.JURI::base().'images/boxs/'.$params->get('icono','').'" alt="'.$datos->titulo.'" title="'.$datos->titulo.'" border="0" />';
			endif;
		endif;
		
		return $datos;
	}

}
