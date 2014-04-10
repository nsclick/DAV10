<?php
/**
 * @version		$Id: inicio.php 2011-05-26 Max Roa Barba
 * @package		Joomla
 * @subpackage	DO
 * @autor		Diseño Objetivo www.do.cl
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
defined( '_DO_GPTI' ) or die( 'El acceso directo a este archivo no está permitido.' );

jimport( 'joomla.application.component.controller' );

/**
 * @package		Joomla
 * @subpackage	DO
 */
class GPTIControllerCorreos extends JController  
{
	/**
	 * Constructor*
	 */
	function __construct()
	{
		parent::__construct( array() );
		
		//$this->registerTask( 'correo',		'display' );
	}

	function display() 
	{
		//ob_start();
		//global $mainframe, $Itemid;
		
		//$lists							= array();
		//$lists['menu-ingresar']		= 23;

//		GPTIHelperCorreo::Encolar( $vars );
		$this->prueba();
	}
	
	function prueba()
	{
		echo "<h1>START</h1>";
		
		global $mainframe, $Itemid;
		$session	=& JFactory::getSession();
		$GPTIuser	=& $session->get( 'GPTI_user', null );
		
		/*$vars['rte'] 		= $GPTIuser->joomla->email ;
		$vars['dst'] 		= 'sebastiangarciat@gmail.com';
		//$vars['cc'] 		= $GPTIuser->joomla->email ;
		$vars['cc'] 		= 'tati_tux@yahoo.es';
		$vars['bcc'] 		= 'sgarcia@do.cl' ;
		$vars['sujeto'] 	= 'PRUEBA GPTI';
		$vars['detalle'] 	= '2.0' ;
		$vars['html'] 		= 'N' ;
		
		GPTIHelperCorreo::Encolar( $vars );*/
		
		GPTIHelperCorreo::Procesar();
		
		echo "<h2>OK</h2>";
	}
}




