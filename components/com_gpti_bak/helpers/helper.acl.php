<?php
/**
 * @version		$Id: helper.acl.php 2011-05-20 Sebastián García Truan $
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

class GPTIHelperACL
{
	function checkUser()
	{
		$user	=& JFactory::getUser();
		
		if( !$user->get('id') )
			return false;
			
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		if( $GPTIuser )
			return true;
		
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_USER_CHECK ( :P_USUARIO, :P_CHECK ); COMMIT; END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$p_check			= 0;
		$p_usuario			= $user->get('id');
		oci_bind_by_name( $stmt, ':P_USUARIO',			$p_usuario,			40 );
		oci_bind_by_name( $stmt, ':P_CHECK',			$p_check,			40 );

		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		if( !$p_check )
			return false;
			
			
		//$storeprocedure		= "BEGIN PKG_GPTI.PROC_USER_GET ( :P_USUARIO, :C_CURSOR, :C_CURSOR_GERENCIAS, :C_CURSOR_PROVEEDORES ); END;";
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_USER_GET ( :P_USUARIO, :C_CURSOR ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$p_usuario			= $user->get('id');
		oci_bind_by_name( $stmt, ':P_USUARIO',			$p_usuario,			40 );
		
		$c_cursor			= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$c_cursor, -1, OCI_B_CURSOR );
		
		/*$c_cursor_gerencias	= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR_GERENCIAS', 		$c_cursor_gerencias, -1, OCI_B_CURSOR );
		
		$c_cursor_proveedores	= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR_PROVEEDORES', 		$c_cursor_proveedores, -1, OCI_B_CURSOR );*/
		
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
				$usr		= new stdClass();
				foreach( $row as $key => $value ) :
					$usr->$key		= $value;
				endforeach;
				
				/*$usr->gerencias		= array();
				if( @!oci_execute( $c_cursor_gerencias ) ) :
					$e 				= oci_error();
					GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
					exit;
				else:
					while ( $ger = oci_fetch_assoc( $c_cursor_gerencias ) ) :
						$gerencia		= new stdClass();
						foreach( $ger as $gerkey => $gervalue ) :
							$gerencia->$gerkey		= $gervalue;
						endforeach;
						$usr->gerencias[]		= $gerencia;
					endwhile;
					oci_free_statement( $c_cursor_gerencias );
				endif;
				
				$usr->proveedores		= array();
				if( @!oci_execute( $c_cursor_proveedores ) ) :
					$e 				= oci_error();
					GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
					exit;
				else:
					while ( $pro = oci_fetch_assoc( $c_cursor_proveedores ) ) :
						$proveedor		= new stdClass();
						foreach( $pro as $prokey => $provalue ) :
							$proveedor->$prokey		= $provalue;
						endforeach;
						$usr->proveedores[]		= $proveedor;
					endwhile;
					oci_free_statement( $c_cursor_proveedores );
				endif;*/
				
			endwhile;
			oci_free_statement( $c_cursor );
		endif;
		
		$usr->joomla	= $user;
		
		$session->set( 'GPTI_user', $usr );
		
		return true;
	}
	
	function check( $p_rol='' )
	{
		$user	=& JFactory::getUser();
		
		if( !$user->get('id') )
			return false;
		
		if( !$p_rol )
			return true;
			
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_USER_ACL ( :P_USUARIO, :P_ROL, :P_CHECK ); COMMIT; END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;

		$p_usuario		= $GPTIuser->USR_ID;
		
		oci_bind_by_name( $stmt, ':P_USUARIO',			$p_usuario,		32 );
		
		oci_bind_by_name( $stmt, ':P_ROL', 				$p_rol,			40 );
		
		$p_check		= null;
		oci_bind_by_name( $stmt, ':P_CHECK', 			$p_check,		40 );

		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		//echo $p_usuario.' - '.$p_rol.' - '.$p_check;
		return (int)$p_check;
		
	}
}
?>