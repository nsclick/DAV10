<?php
/**
 * @version		$Id: clientes.php 2010-06-03 sgarcia $
 * @package		Joomla
 * @subpackage	DO
 * @autor		Diseño Objetivo wwww.do.cl
 * @copyright	Copyright (C) 2006 - 2010 Diseño Objetivo. Todos los derechos reservados.
 * @license		LICENCIA_DO.php
 */

	/**************************************/
	/*                                    */
	/*          Diseño Objetivo           */
	/*     			www.do.cl    	  	  */
	/*   		 contacto@do.cl  		  */
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
class DOControllerConfig extends JController
{
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct( array() );
		
		$this->registerTask( 'apply', 'save' );
	}

	/**
	 * Display the config
	 */
	function display()
	{
		global $mainframe;

		$row 					=& JTable::getInstance('config', 'DO');
		$row->load( 1 );

		$paramsgeneral			= new JParameter( $row->general, JPATH_COMPONENT.DS.'parametros'.DS.'general.xml', 'general' );
		$lists['general']		= $paramsgeneral->render('paramsgeneral');
		
		/*$paramsnoticias			= new JParameter( $row->noticias, JPATH_COMPONENT.DS.'parametros'.DS.'noticias.xml', 'noticias' );
		$lists['noticias']		= $paramsnoticias->render('paramsnoticias');
		
		$paramsbiblioteca		= new JParameter( $row->biblioteca, JPATH_COMPONENT.DS.'parametros'.DS.'biblioteca.xml', 'biblioteca' );
		$lists['biblioteca']	= $paramsbiblioteca->render('paramsbiblioteca');
		
		$paramslicitaciones		= new JParameter( $row->licitaciones, JPATH_COMPONENT.DS.'parametros'.DS.'licitaciones.xml', 'licitaciones' );
		$lists['licitaciones']	= $paramslicitaciones->render('paramslicitaciones');*/
		
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'config.php');
		DOVistaConfig::display( $row, $lists );
	}
	
	/**
	 * Save method
	 */
	function save()
	{
		global $mainframe;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_do&c=config' );

		// Initialize variables
		$db 		=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$post		= JRequest::get( 'post' );
		// fix up special html fields

		$row =& JTable::getInstance('config', 'DO');

		if (!$row->bind( $post )) {
			return JError::raiseWarning( 500, $row->getError() );
		}
			$paramsgeneral		= JRequest::getVar( 'paramsgeneral', null, 'post', 'array' );
			// Build parameter INI string
			if (is_array($paramsgeneral))
			{
				unset( $txt );
				$txt = array ();
				foreach ($paramsgeneral as $k => $v) {
					$txt[] = "$k=$v";
				}
				$row->general = implode("\n", $txt);
			}
			
			/*$paramsnoticias		= JRequest::getVar( 'paramsnoticias', null, 'post', 'array' );
			// Build parameter INI string
			if (is_array($paramsnoticias))
			{
				unset( $txt );
				$txt = array ();
				foreach ($paramsnoticias as $k => $v) {
					$txt[] = "$k=$v";
				}
				$row->noticias = implode("\n", $txt);
			}
			
			$paramsbiblioteca	= JRequest::getVar( 'paramsbiblioteca', null, 'post', 'array' );
			// Build parameter INI string
			if (is_array($paramsbiblioteca))
			{
				unset( $txt );
				$txt = array ();
				foreach ($paramsbiblioteca as $k => $v) {
					$txt[] = "$k=$v";
				}
				$row->biblioteca = implode("\n", $txt);
			}
			
			$paramslicitaciones	= JRequest::getVar( 'paramslicitaciones', null, 'post', 'array' );
			// Build parameter INI string
			if (is_array($paramslicitaciones))
			{
				unset( $txt );
				$txt = array ();
				foreach ($paramslicitaciones as $k => $v) {
					$txt[] = "$k=$v";
				}
				$row->licitaciones = implode("\n", $txt);
			}*/
			
			$row->modificado		= date( "Y-m-d H:i:s");
			$row->modificado_por	= $user->get('id');
		if (!$row->store()) {
			return JError::raiseWarning( 500, $row->getError() );
		}

		switch (JRequest::getCmd( 'task' ))
		{
			case 'apply':
				$link = 'index.php?option=com_do&c=config';
				break;

			case 'save':
			default:
				$link = 'index.php?option=com_do&c=inicio';
				break;
		}

		$this->setRedirect( $link, JText::_( 'Configuración Guardada' ) );
	}

	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_do&c=inicio' );

	}

}
