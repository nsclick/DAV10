<?php
/**
 * @version		$Id: admin.do.php 2010-06-03 sgarcia $
 * @package		Joomla
 * @subpackage	DO
 * @autor		Diseño Objetivo wwww.do.cl
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
defined( '_JEXEC' ) or die( 'Restricted access' );

ob_start();

	// Incluimos el directorio de las tablas
	JTable::addIncludePath( JPATH_COMPONENT.DS.'tablas' );
	
	// controladores
	jimport('joomla.filesystem.folder');
	$db				=& JFactory::getDBO();
	$controladores 	= JFolder::files( JPATH_COMPONENT.DS.'controladores', '.php' );
	$controlador	= JRequest::getCmd( 'c', 'inicio' );
	
	$comDO			=& JComponentHelper::getComponent( 'com_do' );
	
	$query = 'SELECT *' .
			' FROM #__components' .
			' WHERE parent = ' . (int)$comDO->id .
			' AND enabled = 1' .
			' ORDER BY ordering ASC';
	$db->setQuery( $query );
	$submenus	= $db->loadObjectList();
	if( count( $submenus ) ) :
		foreach( $submenus as $indice => $submenu ) :
			$c = str_replace( "option=com_do&c=", "", $submenu->admin_menu_link );
			JSubMenuHelper::addEntry( JText::_( $submenu->name ), "index.php?".$submenu->admin_menu_link, ($c==$controlador)?true:false );
		endforeach;
	endif;
	
	//$db->setQuery();
	
	/*
	if( count( $controladores ) ) :
		foreach( $controladores as $indice => $c ) :
			unset( $controller );
			require_once( JPATH_COMPONENT.DS.'controladores'.DS.$c );
			$controllerName	= 'DOController' . substr( $c, 0,-4 );
			$controller		= new $controllerName();
			JSubMenuHelper::addEntry( JText::_( $controller->get('_name') ), "index.php?option=com_do&amp;c=$c", ($c==$controlador)?true:false );
		endforeach;
	endif;
	*/
	
	// cargamos el controlador
	require_once( JPATH_COMPONENT.DS.'controladores'.DS.$controlador.'.php' );
	$controllerName = 'DOController'.$controlador;
	
	// Create the controller
	unset( $controller );
	$controller = new $controllerName();

	ob_end_clean();
	
	// Perform the Request task
	$controller->execute( JRequest::getCmd('task') );

	// Redirect if set by the controller
	$controller->redirect();
	
	
	
?>