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

class GPTIHelperUsuarios
{
	
	function getUsuarios( $vars=array() )
	{
		//$vars['perfil'];
		//$vars['rol'];
		//$vars['gerencia'];
		//$vars['proveedor'];		
		
		if( !count($vars) )
			return $REQ;

		$session		=& JFactory::getSession();
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		$GPTIuser		=& $session->get( 'GPTI_user', null );
							
		$storeprocedure		= "BEGIN"
							." PKG_GPTI.PROC_USER_LISTA"
							." ( :P_ERROR, :P_UID, :P_PERFIL, :P_ROL, :P_GERENCIA, :P_PROVEEDOR, :C_CURSOR );"
							." COMMIT; END;"
							;
							
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$error				= null;
		oci_bind_by_name( $stmt, ':P_ERROR',			$error,				1000 );
		
		$p_uid				= $vars['uid'];
		oci_bind_by_name( $stmt, ':P_UID',				$p_uid,				40 );
		
		$p_perfil			= $vars['perfil'];
		oci_bind_by_name( $stmt, ':P_PERFIL',			$p_perfil,			32 );
		
		$p_rol				= $vars['rol'];
		oci_bind_by_name( $stmt, ':P_ROL',				$p_rol,				40 );
		
		$p_gerencia			= $vars['gerencia'];
		oci_bind_by_name( $stmt, ':P_GERENCIA', 		$p_gerencia,		40 );
		
		$p_proveedor		= $vars['proveedor'];
		oci_bind_by_name( $stmt, ':P_PROVEEDOR', 		$p_proveedor,		40 );
		
		$c_cursor			= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$c_cursor, -1, OCI_B_CURSOR );
				
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;

		$items = array();
		
		if( @!oci_execute( $c_cursor ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $row = oci_fetch_assoc( $c_cursor ) ) :
				unset($user);
				$user = 	clone(JFactory::getUser());
				$user->load( (int)$row['USR_JOOMLA'] );
				
				$row['joomla'] = $user;
				$items[]	= $row;
				
			endwhile;
			oci_free_statement( $c_cursor );
		endif;
		
		if( $error ) :
			GPTIHelperError::Raise( htmlentities($error, ENT_QUOTES) );
			return;
		endif;

		return $items;
	}
	
}
?>