<?php
/**
 * @version		$Id: helper.correo.php 2011-06-08 Sebastián García Truan $
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

class GPTIHelperCorreo
{
	function Encolar( $vars=array() )
	{
		$session			=& JFactory::getSession();
		$GPTIconn			=& $session->get( 'GPTI_conn', null );
		$GPTIuser			=& $session->get( 'GPTI_user', null );
		
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_CORREOS_ENCOLAR ( :P_ERROR, :P_USUARIO, :P_RTE, :P_DST, :P_CC, :P_BCC, :P_SUJETO, :P_HTML, :P_DETALLE ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$vars['html']		= $vars['html'] ? 'S' : 'N';

		$error				= null;
		oci_bind_by_name( $stmt, ':P_ERROR',			$p_usuario,			32 );
		$p_usuario			= $GPTIuser->USR_ID;
		oci_bind_by_name( $stmt, ':P_USUARIO',			$p_usuario,			32 );
		oci_bind_by_name( $stmt, ':P_RTE', 				$vars['rte'],		999 );
		oci_bind_by_name( $stmt, ':P_DST', 				$vars['dst'],		999 );
		oci_bind_by_name( $stmt, ':P_CC', 				$vars['cc'],		999 );
		oci_bind_by_name( $stmt, ':P_BCC', 				$vars['bcc'],		999 );
		oci_bind_by_name( $stmt, ':P_SUJETO', 			$vars['sujeto'],	254 );
		oci_bind_by_name( $stmt, ':P_HTML', 			$vars['html'],		40 );
		$clobDetalle		= oci_new_descriptor($GPTIconn, OCI_D_LOB);
		oci_bind_by_name( $stmt, ':P_DETALLE',			$clobDetalle,					-1,	OCI_B_CLOB );
		
		/*oci_bind_by_name( $stmt, ':P_ERROR',			$p_usuario,			32 );
		$p_usuario			= $GPTIuser->USR_ID;
		oci_bind_by_name( $stmt, ':P_USUARIO',			$p_usuario,			32 );
		oci_bind_by_name( $stmt, ':P_RTE', 				$vars['rte'],		40 );
		oci_bind_by_name( $stmt, ':P_DST', 				$vars['dst'],		40 );
		oci_bind_by_name( $stmt, ':P_CC', 				$vars['cc'],		40 );
		oci_bind_by_name( $stmt, ':P_BCC', 				$vars['bcc'],		40 );
		oci_bind_by_name( $stmt, ':P_SUJETO', 			$vars['sujeto'],		40 );
		oci_bind_by_name( $stmt, ':P_HTML', 			$vars['html'],		40 );
		oci_bind_by_name( $stmt, ':P_DETALLE', 			$vars['detalle'],		40 );
		*/
		
		if( !oci_execute( $stmt, OCI_NO_AUTO_COMMIT ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return;
		endif;
		
		$clobDetalle->write($vars['detalle']);
		
		if( !oci_commit( $GPTIconn ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return;
		endif;
		
		$clobDetalle->close();
		
		if( $error ) :
			GPTIHelperError::Raise( htmlentities($error, ENT_QUOTES) );
			return;
		endif;
		
		GPTIHelperCorreo::Procesar();
		
		return true;
	}
	
	function Procesar()
	{
		global $mainframe;
		
		$session			=& JFactory::getSession();
		$GPTIconn			=& $session->get( 'GPTI_conn', null );
		$GPTIuser			=& $session->get( 'GPTI_user', null );
		
		$storeprocedure		= "BEGIN"
							." PKG_GPTI.PROC_CORREOS_COLA"
							." ( :P_ERROR, :C_CURSOR );"
							." COMMIT; END;"
							;
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return;
		endif;
		
		$error				= null;
		oci_bind_by_name( $stmt, ':P_ERROR',			$error,			32 );
		
		$c_cursor			= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$c_cursor, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return;
		endif;

		if( $error ) :
			GPTIHelperError::Raise( htmlentities($error, ENT_QUOTES) );
			return;
		endif;
		
				
		$rows		= array();
		$procesados	= array();
		
		if( @!oci_execute( $c_cursor ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $fila = oci_fetch_assoc( $c_cursor ) ) :
			unset($row);
			$row			= new stdClass();
				foreach( $fila as $key => $value ) :
					if( ( $key == 'COR_DETALLE' ) && is_object( $value ) ) :
						$row->$key		= $value->load();
					else :
						$row->$key		= $value;
					endif;
				endforeach;
			$rows[]			= $row;
			endwhile;
			oci_free_statement( $c_cursor );
		endif;
		

		$sitename 			= $mainframe->getCfg( 'sitename' );
		$mailfrom 			= $mainframe->getCfg( 'mailfrom' );
		$fromname 			= $mainframe->getCfg( 'fromname' );
		$siteURL			= JURI::base();		

		if( count( $rows ) ) :
			foreach( $rows as $correo ) :
				$correo->COR_DST	= explode(",",$correo->COR_DST);
				$correo->COR_CC		= $correo->COR_CC ? explode(",",$correo->COR_CC) : $correo->COR_CC;
				$correo->COR_BCC	= $correo->COR_BCC ? explode(",",$correo->COR_BCC) : $correo->COR_BCC;
				if( JUtility::sendMail($correo->COR_RTE, $fromname, $correo->COR_DST, $correo->COR_SUJETO, $correo->COR_DETALLE, $correo->COR_HTML == 'S' ? 1:0, $correo->COR_CC, $correo->COR_BCC ) ) :
					$procesados[]	= $correo->COR_ID;
				endif;
			endforeach;
		endif;
		
		$storeprocedure		= "BEGIN"
							." PKG_GPTI.PROC_CORREOS_PROCESADOS"
							." ( :P_ERROR, :P_IDS );"
							." COMMIT; END;"
							;
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return;
		endif;
		
		$error				= null;
		oci_bind_by_name( $stmt, ':P_ERROR',			$error,			1000 );
		
		$p_ids				= implode( ",",$procesados );
		oci_bind_by_name( $stmt, ':P_IDS',				$p_ids,			1000 );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return;
		endif;

		if( $error ) :
			GPTIHelperError::Raise( htmlentities($error, ENT_QUOTES) );
			return;
		endif;
		
		return true;
	}	
}
?>