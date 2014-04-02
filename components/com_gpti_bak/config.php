<?php
/**
 * @version		$Id: config.php 2011-05-20 Sebastián García Truan $
 * @package		Joomla
 * @subpackage	GPTI
 * @autor		Diseño Objetivo www.do.cl
 * @copyright	Copyright (C) 2006 - 2011 Diseño Objetivo. Todos los derechos reservados.
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
defined( '_JEXEC' ) or die( 'El acceso directo a este archivo no está permitido.' );
define( '_DO_GPTI', 1 );

require_once( JPATH_ROOT.DS.'components'.DS.'com_gpti'.DS.'helpers'.DS.'helper.error.php' );
		
$GPTI['oci']['host']			= 'localhost';
$GPTI['oci']['puerto']			= '1521';
$GPTI['oci']['usuario']			= 'PORTALDAV';
$GPTI['oci']['clave']			= '4gH6*/d4j3Er';
$GPTI['oci']['sid']				= 'xe';
$GPTI['oci']['charset']			= 'AL32UTF8';
$GPTI['oci']['db']				= ""
								. "(DESCRIPTION =\n"
								. "  (ADDRESS_LIST =\n"
								. "    (ADDRESS = (PROTOCOL = TCP)(HOST = ".$GPTI['oci']['host'].")(PORT = ".$GPTI['oci']['puerto']."))\n"
								. "  )\n"
								. "  (CONNECT_DATA =\n"
								. "    (SID = ".$GPTI['oci']['sid'].")\n"
								. "  )\n"
								. ")"
								;

$session			=& JFactory::getSession();
if( !$session->get('GPTI_conn') ) :
	if(!$GPTIconn		= @oci_connect( $GPTI['oci']['usuario'], $GPTI['oci']['clave'], $GPTI['oci']['db'], $GPTI['oci']['charset'] ) ):
		$e 				= oci_error();
		GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
		exit;
	endif;
	$session->set('GPTI_conn', $GPTIconn);
endif;
?>
