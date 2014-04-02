<?php
/**
 * @version		$Id: tareas.php 2011-06-08 Sebastián García Truan $
 * @package		GPTI
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

/**
 * @package		Joomla
 * @subpackage	DO
 */
class GPTITareas extends JTable
{
	
	var $TAR_ID						= null;
	var $TAR_REQ					= null;
	var $TAR_NOMBRE					= null;
	var $TAR_FECHA_INICIO			= null;
	var $TAR_FECHA_TERMINO			= null;
	var $TAR_HH_ESTIMADA			= null;
	var $TAR_HH_INFORMADA			= null;
	var $TAR_RECURSO				= null;
	var $TAR_OBSERVACIONES			= null;
	var $TAR_FECHA_INICIO_REAL		= null;
	var $TAR_FECHA_TERMINO_REAL		= null;
	var $TAR_FECHA_CREACION			= null;
	var $TAR_CREADOR				= null;
	var $TAR_TIPO					= null;
	var $TAR_OBS_EJECUTOR			= null;

	
	function __construct( &$_db )
	{
		parent::__construct( '#__gpti_tareas', 'tar_id', $_db );
	}
	function bind( $datos=array() )
	{
		parent::bind( $datos );

		$this->TAR_FECHA_INICIO			= !isset($datos['TAR_FECHA_INICIO']) && $this->TAR_FECHA_INICIO ? date( "Y-m-d", strtotime( $this->TAR_FECHA_INICIO )) : $this->TAR_FECHA_INICIO;
		$this->TAR_FECHA_TERMINO		= !isset($datos['TAR_FECHA_TERMINO']) && $this->TAR_FECHA_TERMINO ? date( "Y-m-d", strtotime( $this->TAR_FECHA_TERMINO )) : $this->TAR_FECHA_TERMINO;
		
		$this->TAR_FECHA_INICIO_REAL	= !isset($datos['TAR_FECHA_INICIO_REAL']) && $this->TAR_FECHA_INICIO_REAL ? date( "Y-m-d", strtotime( $this->TAR_FECHA_INICIO_REAL )) : $this->TAR_FECHA_INICIO_REAL;
		$this->TAR_FECHA_TERMINO_REAL	= !isset($datos['TAR_FECHA_TERMINO_REAL']) && $this->TAR_FECHA_TERMINO_REAL ? date( "Y-m-d", strtotime( $this->TAR_FECHA_TERMINO_REAL )) : $this->TAR_FECHA_TERMINO_REAL;
	}
	
	function get( $id=0 )
	{
		if( !$id )
			return false;
			
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_TAREAS_LOAD ( :P_TAREA, :C_CURSOR ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$p_tarea			= $id;
		oci_bind_by_name( $stmt, ':P_TAREA',			$p_tarea,			40 );
		
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
					if( ($key == 'TAR_OBSERVACIONES' || $key == 'TAR_OBS_EJECUTOR') && is_object( $value ) ) :
						$this->$key		= $value->load();
					else :
						$this->$key		= $value;
					endif;
				endforeach;
		
				unset($user);
				$user 		= clone(JFactory::getUser());
				$user->load( $this->CREADOR );
				$this->CREADOR = $user;
				
				if( $this->RECURSO ) :
					unset($user);
					$user 		= clone(JFactory::getUser());
					$user->load( $this->RECURSO );
					$this->RECURSO = $user;
				endif;
				
			endwhile;				
			oci_free_statement( $c_cursor );
		endif;
		
		return true;
	}
	
	function save()
	{
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_TAREAS_SAVE ( :P_ERROR, :P_ID, :P_REQ, :P_NOMBRE, :P_FINICIO, :P_FTERMINO, :P_HHESTIMADA"
							. ", :P_HHINFORMADA, :P_RECURSO, :P_OBS, :P_FINICIO_REAL, :P_FTERMINO_REAL, :P_USUARIO, :P_TIPO, :P_OBS_EJECUTOR ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return;
		endif;
		
		$this->TAR_CREADOR				=  $GPTIuser->USR_ID;
		
		$error							= null;
		oci_bind_by_name( $stmt, ':P_ERROR',			$error,							200 );
		oci_bind_by_name( $stmt, ':P_ID',				$this->TAR_ID,					40 );
		oci_bind_by_name( $stmt, ':P_REQ',				$this->TAR_REQ,					40 );
		oci_bind_by_name( $stmt, ':P_NOMBRE',			$this->TAR_NOMBRE,				40 );
		oci_bind_by_name( $stmt, ':P_FINICIO',			$this->TAR_FECHA_INICIO,		40 );
		oci_bind_by_name( $stmt, ':P_FTERMINO',			$this->TAR_FECHA_TERMINO,		40 );
		oci_bind_by_name( $stmt, ':P_HHESTIMADA',		$this->TAR_HH_ESTIMADA,			40 );
		oci_bind_by_name( $stmt, ':P_HHINFORMADA',		$this->TAR_HH_INFORMADA,		40 );
		oci_bind_by_name( $stmt, ':P_RECURSO',			$this->TAR_RECURSO,				40 );
		
		$clobObs						= oci_new_descriptor($GPTIconn, OCI_D_LOB);
		oci_bind_by_name( $stmt, ':P_OBS',				$clobObs,						-1,	OCI_B_CLOB );
				
		//oci_bind_by_name( $stmt, ':P_OBS',				$this->TAR_OBSERVACIONES,		1000 );
		oci_bind_by_name( $stmt, ':P_FINICIO_REAL',		$this->TAR_FECHA_INICIO_REAL,	40 );
		oci_bind_by_name( $stmt, ':P_FTERMINO_REAL',	$this->TAR_FECHA_TERMINO_REAL,	40 );
		oci_bind_by_name( $stmt, ':P_USUARIO',			$this->TAR_CREADOR,				40 );
		oci_bind_by_name( $stmt, ':P_TIPO',				$this->TAR_TIPO,				40 );

		$clobObsEjecutor				= oci_new_descriptor($GPTIconn, OCI_D_LOB);
		oci_bind_by_name( $stmt, ':P_OBS_EJECUTOR',		$clobObsEjecutor,				-1,	OCI_B_CLOB );
		
		if( !oci_execute( $stmt, OCI_NO_AUTO_COMMIT ) ) :
			$e 							= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return;
		endif;
		
		$clobObs->write($this->TAR_OBSERVACIONES);
		$clobObsEjecutor->write($this->TAR_OBS_EJECUTOR);
		
		if( !oci_commit( $GPTIconn ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return;
		endif;
		
		$clobObs->close();
		$clobObsEjecutor->close();
		
		if( $error ) :
			GPTIHelperError::Raise( htmlentities($error, ENT_QUOTES) );
			return;
		endif;
							
		return true;
	}
	
}
?>
