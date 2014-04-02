<?php
/**
 * @version		$Id: helper.error.php 2011-05-20 Sebastián García Truan $
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
defined( '_DO_GPTI' ) or die( 'El acceso directo a este archivo no está permitido.' );

class GPTIHelperLog
{
	function Log( $p_usuario, $p_fuente, $p_detalle )
	{
		$session		=& JFactory::getSession();
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN"
							." PKG_GPTI.PROC_LOG"
							." ( :P_USUARIO, :P_FUENTE, :P_DETALLE );"
							." COMMIT; END;"
							;
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;

		oci_bind_by_name( $stmt, ':P_USUARIO',			$p_usuario,		32 );
		oci_bind_by_name( $stmt, ':P_FUENTE', 			$p_fuente,		40 );
		oci_bind_by_name( $stmt, ':P_DETALLE', 			$p_detalle,		40 );

		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		return true;
	}	
}
?>