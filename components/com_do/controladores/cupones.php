<?php
/**
 * @version		$Id: cupones.php 2011-07-04 Sebastián García Truan $
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
defined( '_JEXEC' ) or die( 'Restricted access' ); 

jimport( 'joomla.application.component.controller' );

/**
 * @package		Joomla
 * @subpackage	DO
 */
class DOControllerCupones extends JController  
{ 
	/**
	 * Constructor*
	 */	 
 
	function __construct()
	{
		parent::__construct( array() );
		
		$this->registerTask( 'reactivar',		'editar' );
		$this->registerTask( 'desactivar',		'editar' );
	}

	function display() 
	{ 
		ob_start();
		global $mainframe, $Itemid;
		
		$user 					=& JFactory::getUser();		
		$usuarioshabilitados	= array('funcionario1','vheufemann','MACA','12583945','15272359');
		if( array_search($user->get('username'), $usuarioshabilitados) === false ) :
			$mainframe->redirect( "index.php" );
		endif;
		
		$lists					= array();
		
		$context				= 'com_do.cupones.list.';
		$lists['limit']			= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$lists['limitstart'] 	= $mainframe->getUserStateFromRequest( $context.'limitstart', 'start', 0, 'int' );
		
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'cupones.php');
		ob_end_clean();
		DoVistaCupones::display( $rows, $servicios, $lists );
	}
	
	function lista()
	{
		ob_start();
		global $mainframe, $Itemid;
		
		$user 					=& JFactory::getUser();		
		$usuarioshabilitados	= array('funcionario1','vheufemann','MACA','12583945','15272359','mroa');
		if( array_search($user->get('username'), $usuarioshabilitados) === false ) :
			$mainframe->redirect( "index.php" );
		endif;
		
		$lists					= array();
		
		$context				= 'com_do.cupones.list.';
		$lists['limit']			= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$lists['limitstart'] 	= $mainframe->getUserStateFromRequest( $context.'limitstart', 'start', 0, 'int' );
		
		$cupones				=& JTable::getInstance('cupones', 'DO');
		$rows					= $cupones->lista( $lists );
		
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $lists['total'], $lists['limitstart'], $lists['limit'] );
		
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'cupones.php');
		ob_end_clean();
		DoVistaCupones::lista( $rows, $lists, $pageNav );
	}
	
	function editar()
	{
		global $mainframe, $Itemid;
		
		$user 					=& JFactory::getUser();		
		$usuarioshabilitados	= array('funcionario1','vheufemann','MACA','12583945','15272359');
		if( array_search($user->get('username'), $usuarioshabilitados) === false ) :
			$mainframe->redirect( "index.php" );
		endif;
		
		$task					= JRequest::getCmd('task');
		$id						= (int)JRequest::getInt('id',0);
		
		$cupones				=& JTable::getInstance('cupones', 'DO');
		$cupones->load( $id );
		$cupones->impresion		= $task=='reactivar' ? '0000-00-00 00:00:00' : date("Y-m-d H:i:s");
		$cupones->store();
		
		$this->lista();
	}
	
}