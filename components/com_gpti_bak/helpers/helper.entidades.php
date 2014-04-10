<?php
/**
 * @version		$Id: helper.entidades.php 2011-05-20 Sebastián García Truan $
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

class GPTIHelperEntidades
{
	function getRequerimiento( $id=0 )
	{
		$REQ		=& JTable::getInstance('requerimientos', 'GPTI');
		
		if( !$id )
			return $REQ;
			
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_REQS_LOAD ( :P_REQ, :C_CURSOR ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$p_req				= $id;
		oci_bind_by_name( $stmt, ':P_REQ',			$p_req,			40 );
		
		$c_cursor			= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$c_cursor, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		if( @!oci_execute( $c_cursor ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $row = oci_fetch_assoc( $c_cursor ) ) :
				foreach( $row as $key => $value ) :
					$REQ->$key		= $value;
				endforeach;
			endwhile;
			oci_free_statement( $c_cursor );
		endif;
		
		return $REQ;
	}
}
?>