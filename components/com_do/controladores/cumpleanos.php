<?php
/**
 * @version		$Id: cumpleanos.php 2010-07-22 sgarcia $
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
class DOControllerCumpleanos extends JController  
{ 
	/**
	 * Constructor*
	 */	 
 
	function __construct()
	{
		parent::__construct( array() );
	}

	function display() 
	{ 
		//ob_start();
		global $mainframe, $Itemid;
		
		$app					=& JFactory::getApplication();
		$user 					=& JFactory::getUser();	
		$app					=& JFactory::getApplication();
		$doc					=& JFactory::getDocument();
		$oracle					=& JTable::getInstance('oracle', 'DO');

		$lists					= array();
		$filtro					= new stdClass;
		$filtro->cumplemes		= date('m');
		if( !$rows				= $oracle->personas( $filtro ) ) :
			$lists['error']		= $oracle->_error;
		endif;
		
		
		$filtrodos				= new stdClass;
		$filtrodos->unidad		= JRequest::getVar( 'filtro_unidad', 'Ingrese aquí servicio', 'request', 'string' ) != 'Ingrese aquí servicio' ? JRequest::getVar( 'filtro_unidad', '', 'request', 'string' ) : '';
		$lista					= false;
		if( $filtrodos->unidad ) :
			$lists['unidad']		= $filtrodos->unidad;
			//$filtrodos->cumplemes	= date('m');
			if( !$lista				= $oracle->personas( $filtrodos ) ) :
				$lists['errordos']	= $oracle->_error;
			endif;
		endif;
		
		$lists['msj']			= JText::_( base64_decode( JRequest::getVar( 'msj', base64_encode(''), 'request', 'string' ) ) );
		$lists['tmpl']			= JURI::base() . 'templates/' . $app->getTemplate();
		$lists['mes']			= fixMes(strftime("%B")).' '.strftime("%Y");
		
		if( $lists['errordos'] ) :
			$lists['msj']		= $lists['errordos']."<br /><br />".$lists['msj'];
		elseif( $filtrodos->unidad && !$lists['errordos'] && !$lista ) :
			$lists['msj']		= "No se encontraron cumpleaños para el servicio &quot;".$filtrodos->unidad."&quot;<br /><br />".$lists['msj'];
			unset( $rows );
		endif;
		if( $lists['error'] ) :
			$lists['msj']		= $lists['error']."<br /><br />".$lists['msj'];
		endif;
		
		// se agregan los scripts necesarios
		//JHTML::_('behavior.mootools');
		//$doc->addScript( 'components/com_do/includes/js/cumpleanos.js' );
		
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'cumpleanos.php');
		//ob_end_clean();
		DoVistaCumpleanos::display( $rows, $lista, $lists );
	}

	function ver() 
	{ 
		ob_start();
		global $mainframe, $Itemid;
		
		$session				=& JFactory::getSession();
		$user 					=& JFactory::getUser();	
		$app					=& JFactory::getApplication();
		$doc					=& JFactory::getDocument();
		$oracle					=& JTable::getInstance('oracle', 'DO');
		$cumpleanos				=& JTable::getInstance('cumpleanos', 'DO');
		$lists					= array();
		
		$filtro					= new stdClass;
		$filtro->id				= JRequest::getInt( 'id', 0, 'request' );
		
		if( !$row				= $oracle->funcionario( $filtro->id ) ) :
			$lists['error']		= $oracle->_error;
		endif;
		
		$rows					= $cumpleanos->lista( $filtro->id );
		
		$lists['msj']			= JText::_( base64_decode( JRequest::getVar( 'msj', '', 'request', 'string' ) ) );
		$lists['tmpl']			= JURI::base() . 'templates/' . $app->getTemplate();
		if( $lists['error'] ) :
			$lists['msj']		= $lists['error']."<br /><br />".$lists['msj'];
		endif;
		
		// se agregan los scripts necesarios
		//JHTML::_('behavior.mootools');
		//$doc->addScript( 'components/com_do/includes/js/cumpleanos_scroll.js' );
		
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'cumpleanos.php');
		ob_end_clean();
		DoVistaCumpleanos::ver( $row, $rows, $lists );
	}

	function saludar() 
	{ 
		ob_start();
		global $mainframe, $Itemid;
		
		$session				=& JFactory::getSession();
		$user 					=& JFactory::getUser();	
		$app					=& JFactory::getApplication();
		$doc					=& JFactory::getDocument();
		$oracle					=& JTable::getInstance('oracle', 'DO');
		$cumpleanos				=& JTable::getInstance('cumpleanos', 'DO');
		
		$filtro					= new stdClass;
		$filtro->id				= JRequest::getInt( 'id', 0, 'request' );
		
		if( !$row				= $oracle->funcionario( $filtro->id ) ) :
			$lists['error']		= $oracle->_error;
		endif;
		
		$lists					= array();
		$lists['msj']			= JText::_( base64_decode( JRequest::getVar( 'msj', '', 'request', 'string' ) ) );
		$lists['tmpl']			= JURI::base() . 'templates/' . $app->getTemplate();
		if( $lists['error'] ) :
			$lists['msj']		= $lists['error']."<br /><br />".$lists['msj'];
		endif;
		
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'cumpleanos.php');
		ob_end_clean();
		DoVistaCumpleanos::saludar( $row, $lists );
	}
	
	function saludo()
	{
		global $mainframe, $Itemid;
		
		$session				=& JFactory::getSession();
		$user 					=& JFactory::getUser();	
		$app					=& JFactory::getApplication();
		$doc					=& JFactory::getDocument();
		$db						=& JFactory::getDBO();
		$cumpleanos				=& JTable::getInstance('cumpleanos', 'DO');
		
		$id						= JRequest::getInt( 'id', 0, 'request' );
		$anno					= date("Y");
		$remitente				= $user->get('name');
		$mensaje				= JRequest::getVar( 'comentarios', '', 'request' );
		$fecha					= date("Y-m-d H:i:s");
		$query		= "INSERT INTO #__do_cumpleanos (`funcionario`,`anno`,`remitente`,`mensaje`,`fecha`) VALUES"
					." ($id,'$anno','$remitente','$mensaje','$fecha')"
					;
		$db->setQuery( $query );
		if( !$db->query() ) :
			$msj = base64_encode(JText::_( $db->getError()));
			$mainframe->redirect( "index.php?option=com_do&Itemid=$Itemid&c=cumpleanos&task=ver&id=$id&msj=$msj" );
		endif;
		
		$msj	= base64_encode('Su saludo de cumpleaños ha sido enviado con éxito');
		$mainframe->redirect( "index.php?option=com_do&Itemid=$Itemid&c=cumpleanos&task=ver&id=$id&msj=$msj" );
	}

}
