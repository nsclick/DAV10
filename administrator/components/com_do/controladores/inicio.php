<?php
/**
 * @version		$Id: inicio.php 2010-06-03 sgarcia $
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
class DOControllerInicio extends JController
{
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct( array() );
		
		$this->set('_name', 'Inicio');
	}

	/**
	 * Display the home
	 */
	function display()
	{
		global $mainframe;

		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'inicio.php');
		DOVistaInicio::display();
	}
}
