<?php
/**
 * @version		$Id: reconocimientos.php 2010-07-22 sgarcia $
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
class DOControllerReconocimientos extends JController  
{ 
	/**
	 * Constructor*
	 */	 
 
	function __construct()
	{
		parent::__construct( array() );
		
		$this->registerTask( 'historial',	'display' );
		$this->registerTask( 'tresmeses',	'display' );
		$this->registerTask( 'seismeses',	'display' );
		$this->registerTask( 'editar',		'crear' );
	}

	function display() 
	{ 
		ob_start();
		global $mainframe, $Itemid;
		
		$app					=& JFactory::getApplication();
		$user 					=& JFactory::getUser();	
		$app					=& JFactory::getApplication();
		$doc					=& JFactory::getDocument();
		$reconocimientos		=& JTable::getInstance('reconocimientos', 'DO');
		
		switch( JRequest::getCmd('task') ) :
			case 'historial'	: $limit = 0;			break;
			case 'tresmeses'	: $limit = '3meses';	break;
			case 'seismeses'	: $limit = '6meses';	break;
			default				: $limit = 20;			break;
		endswitch;
		
		$filtro					= new stdClass;
		if( $limit == '3meses' || $limit == '6meses' ) :
			$meses		= (int)str_replace("meses","",$limit);
			$limit		= 0;
			$filtro->desde		= date("Y-m-d 00:00:00", strtotime("-$meses month") );
		endif;
		$filtro->limit			= $limit;
		$filtro->unidad			= JRequest::getVar( 'servicio', '', 'request', 'string' );
		
		$rows					= $reconocimientos->lista( $filtro );
		$servicios				= $reconocimientos->getServicios();			
		
		$lists					= array();
		$lists['msj']			= JText::_( base64_decode( JRequest::getVar( 'msj', base64_encode(''), 'request', 'string' ) ) );
		$lists['tmpl']			= JURI::base() . 'templates/' . $app->getTemplate();
		$lists['servicio']		= $filtro->unidad;
		
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'reconocimientos.php');
		ob_end_clean();
		//echo '<pre>'; print_r($rows); exit;
		DoVistaReconocimientos::display( $rows, $servicios, $lists );
	}
	
	function mantener()
	{
		ob_start();
		global $mainframe, $Itemid;
		
		$session				=& JFactory::getSession();
		$user 					=& JFactory::getUser();	
		$app					=& JFactory::getApplication();
		$doc					=& JFactory::getDocument();
		$oracle					=& JTable::getInstance('oracle', 'DO');
		$reconocimientos		=& JTable::getInstance('reconocimientos', 'DO');
		
		$filtro					= new stdClass;
		//$filtro->id				= JRequest::getInt( 'id', 0, 'request' );
		$rows					= array();
		
		if( !$row				= $oracle->funcionario( $user->get('username') ) ) :
			$lists['error']		= $oracle->_error;
		else :
			$filtro->jefe		= $user->get('username');
			$rows				= $reconocimientos->getLista( $filtro );
		endif;
		
		$lists					= array();
		$lists['msj']			= JText::_( base64_decode( JRequest::getVar( 'msj', '', 'request', 'string' ) ) );
		$lists['rec_rut']			= JRequest::getInt( 'funcionario', 0, 'request' );
		$lists['rec_comentarios']	= JRequest::getVar( 'comentarios', '', 'request', 'string' );
		$lists['tmpl']			= JURI::base() . 'templates/' . $app->getTemplate();
		if( $lists['error'] ) :
			$lists['msj']		= $lists['error']."<br /><br />".$lists['msj'];
		endif;
		
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'reconocimientos.php');
		ob_end_clean();
		DoVistaReconocimientos::mantener( $rows, $lists );
	}

	function crear() 
	{ 
		ob_start();
		global $mainframe, $Itemid;
		
		$session				=& JFactory::getSession();
		$user 					=& JFactory::getUser();	
		$app					=& JFactory::getApplication();
		$doc					=& JFactory::getDocument();
		$oracle					=& JTable::getInstance('oracle', 'DO');
		$reconocimiento			=& JTable::getInstance('reconocimientos', 'DO');
		
		$filtro					= new stdClass;
		$filtro->id				= JRequest::getInt( 'id', 0, 'request' );
		
		$reconocimiento->load( $filtro->id );
		
		if( !$row				= $oracle->funcionario( $user->get('username') ) ) :
			$lists['error']		= $oracle->_error;
		endif;
		
		$reconocimiento->rut	= JRequest::getInt( 'funcionario', 0, 'request' ) ? JRequest::getInt( 'funcionario', 0, 'request' ) : $reconocimiento->rut;
		$reconocimiento->mensaje= JRequest::getVar( 'comentarios', '', 'request', 'string' ) ? JRequest::getVar( 'comentarios', '', 'request', 'string' ) : $reconocimiento->mensaje;
		
		$lists					= array();
		$lists['msj']			= JText::_( base64_decode( JRequest::getVar( 'msj', '', 'request', 'string' ) ) );
//		$lists['rec_rut']			= JRequest::getInt( 'funcionario', 0, 'request' );
//		$lists['rec_comentarios']	= JRequest::getVar( 'comentarios', '', 'request', 'string' );
		$lists['tmpl']			= JURI::base() . 'templates/' . $app->getTemplate();
		if( $lists['error'] ) :
			$lists['msj']		= $lists['error']."<br /><br />".$lists['msj'];
		endif;
		
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'reconocimientos.php');
		ob_end_clean();
		DoVistaReconocimientos::crear( $reconocimiento, $row, $lists );
	}
	
	function preview()
	{
		global $mainframe, $Itemid;
		
		$session				=& JFactory::getSession();
		$user 					=& JFactory::getUser();	
		$app					=& JFactory::getApplication();
		$doc					=& JFactory::getDocument();
		$db						=& JFactory::getDBO();
		
		$row					= new stdClass;
		$row->id				= JRequest::getInt( 'id', 0, 'request' );
		$funcionario			= JRequest::getVar( 'funcionario', '', 'request' );
		list( $row->rut, $row->nombre, $row->unidad )	= explode( ":", $funcionario );
		$row->rut				= (int) substr( $row->rut, 0, -2 );
		$row->jefe				= $user->get('username');
		$row->jefenombre		= $user->get('name');
		$row->mensaje			= JRequest::getVar( 'comentarios', '', 'request' );

				
		$lists					= array();
		$lists['msj']			= JText::_( base64_decode( JRequest::getVar( 'msj', base64_encode(''), 'request', 'string' ) ) );
		$lists['tmpl']			= JURI::base() . 'templates/' . $app->getTemplate();
		
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'reconocimientos.php');
		ob_end_clean();
		DoVistaReconocimientos::preview( $row, $lists );
	}

	function reconocer()
	{
		global $mainframe, $Itemid;
		
		$session				=& JFactory::getSession();
		$user 					=& JFactory::getUser();	
		$app					=& JFactory::getApplication();
		$doc					=& JFactory::getDocument();
		$db						=& JFactory::getDBO();
		
		$row					=& JTable::getInstance('reconocimientos', 'DO');
		$row->load( JRequest::getInt( 'id', 0, 'request' ) );
		$row->rut				= JRequest::getVar( 'funcionario', '', 'request' );
		$row->nombre			= JRequest::getVar( 'nombre', '', 'request' );
		$row->unidad			= JRequest::getVar( 'unidad', '', 'request' );
		$row->jefe				= $user->get('username');
		$row->jefenombre		= $user->get('name');
		$row->mensaje			= JRequest::getVar( 'comentarios', '', 'request' );
		$row->fecha				= date("Y-m-d H:i:s");
		
		if( !$row->store() ) :
			$msj = base64_encode(JText::_( $row->getError()));
			$mainframe->redirect( "index.php?option=com_do&Itemid=$Itemid&c=reconocimientos&task=mantener&msj=$msj" );
		endif;
		
		/*
		$funcionario			= JRequest::getVar( 'funcionario', '', 'request' );
		list( $rut, $nombre, $unidad )	= explode( ":", $funcionario );
		$rut					= (int) substr( $rut, 0, -2 );
		*/
		/*
		$rut					= JRequest::getVar( 'funcionario', '', 'request' );
		$nombre					= JRequest::getVar( 'nombre', '', 'request' );
		$unidad					= JRequest::getVar( 'unidad', '', 'request' );
		$jefe					= $user->get('username');
		$jefenombre				= $user->get('name');
		$mensaje				= JRequest::getVar( 'comentarios', '', 'request' );
		$fecha					= date("Y-m-d H:i:s");
		$query		= "INSERT INTO #__do_reconocimientos (`rut`,`nombre`,`unidad`,`jefe`,`jefenombre`,`mensaje`,`fecha`) VALUES"
					." ($rut,'$nombre','$unidad',$jefe,'$jefenombre','$mensaje','$fecha')"
					;
		$db->setQuery( $query );
		if( !$db->query() ) :
			$msj = base64_encode(JText::_( $db->getError()));
			$mainframe->redirect( "index.php?option=com_do&Itemid=$Itemid&c=reconocimientos&task=crear&msj=$msj" );
		endif;
		*/
		
		$msj	= JRequest::getInt( 'id', 0, 'request' ) ? base64_encode('El reconocimiento ha sido actualizado con éxito') : base64_encode('El reconocimiento ha sido creado con éxito');
		$mainframe->redirect( "index.php?option=com_do&Itemid=$Itemid&c=reconocimientos&task=mantener&msj=$msj" );
	}
	
	function eliminar()
	{
		global $mainframe, $Itemid;
		
		$session				=& JFactory::getSession();
		$user 					=& JFactory::getUser();	
		$app					=& JFactory::getApplication();
		$doc					=& JFactory::getDocument();
		$db						=& JFactory::getDBO();
		
		$id		= JRequest::getInt( 'id', 0, 'request' );
		$query	= "DELETE FROM #__do_reconocimientos WHERE `id` = $id";
		$db->setQuery( $query );
		if( !$db->query() ) :
			$msj = base64_encode(JText::_( $db->getError()));
			$mainframe->redirect( "index.php?option=com_do&Itemid=$Itemid&c=reconocimientos&task=mantener&msj=$msj" );
		endif;
		
		$msj	= base64_encode('El reconocimiento ha sido eliminado con éxito');
		$mainframe->redirect( "index.php?option=com_do&Itemid=$Itemid&c=reconocimientos&task=mantener&msj=$msj" );
	}

	function ver() 
	{ 
		ob_start();
		global $mainframe, $Itemid;
		
		$app					=& JFactory::getApplication();
		$user 					=& JFactory::getUser();	
		$app					=& JFactory::getApplication();
		$doc					=& JFactory::getDocument();
		$reconocimiento			=& JTable::getInstance('reconocimientos', 'DO');
		$id						= JRequest::getInt('id',0,'request');
		
		//$reconocimiento->load( $id );
		$reconocimiento->loadExt($id);
				
		$lists					= array();
		$lists['msj']			= JText::_( base64_decode( JRequest::getVar( 'msj', base64_encode(''), 'request', 'string' ) ) );
		$lists['tmpl']			= JURI::base() . 'templates/' . $app->getTemplate();
		
		$status					= "status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=960,height=500,directories=no,location=no";
		$link					= JURI::base() . "index.php?option=com_do&amp;c=reconocimientos&amp;task=ver&amp;tmpl=componente&amp;id=$id&amp;Itemid=" . $Itemid;
		$lists['imprimir']		= JRequest::getVar( 'tmpl', '', "REQUEST" ) == "componente" ? "javascript:window.print();" : "javascript:window.open('".$link."','win2','".$status."');";
		$lists['puedeImprimir']	= $reconocimiento->jefe == $user->get('username') ? true : false;
		
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'reconocimientos.php');
		ob_end_clean();

		DoVistaReconocimientos::ver( $reconocimiento, $lists );
	}

}