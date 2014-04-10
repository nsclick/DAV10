<?php
/**
 * @version		$Id: do.php 2010-07-22 sgarcia $
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
	
// sin acceso directo
defined( '_JEXEC' ) or die( 'El acceso directo a este archivo no está permitido.' );

// Iniciamos el buffer de salida
ob_start();

	global $mainframe;
	
	// Set the table directory
	JTable::addIncludePath( JPATH_COMPONENT_ADMINISTRATOR.DS.'tablas' );
	
	// html comun
	require_once( JPATH_COMPONENT.DS.'do.html.php' );

	// controlador y tarea
	$Itemid 	= intval(JRequest::getVar('Itemid', '' ,"REQUEST"));
	$menu 		= JTable::getInstance('Menu');
	$menu->load( $Itemid );
	$params		= new JParameter($menu->params);
	
	// controlador y tarea
	$c 		= strval($params->get( 'controlador','personas' ));
	
	$tarea	= JRequest::getCmd( 'task', $params->get( 'tarea','' ) );
	JRequest::setVar('task', $tarea);
		
	$controllerName = JRequest::getCmd( 'c', $c );

			$Gastronomia->_controlador 	= $controllerName;
			$Gastronomia->_task			= $tarea;

			require_once( JPATH_COMPONENT.DS.'controladores'.DS.$controllerName.'.php' );
			$controllerName = 'DOController'.$controllerName;
		
			// Create the controller
			$controller = new $controllerName();
		
			// Lipiamos el buffer de salida
			ob_end_clean();
		
			// Perform the Request task
			$controller->execute( JRequest::getCmd( 'task' ) );
			
			// Redirect if set by the controller
			$controller->redirect();
		
	
?>