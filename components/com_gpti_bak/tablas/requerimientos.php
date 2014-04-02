<?php
/**
 * @version		$Id: requerimientos.php 2011-06-01 Sebastián García Truan $
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
class GPTIRequerimientos extends JTable
{
	var $REQ_ID						= null;
	var $REQ_PROYECTO				= null;
	var $REQ_NOMBRE					= null;
	var $REQ_PRIORIDAD				= null;
	var $REQ_PROPOSITO				= null;
	var $REQ_FECHA_ENTREGA			= null;
	var $REQ_DIAGNOSTICO			= null;
	var $REQ_USUARIO				= null;
	var $REQ_FECHA_CREACION			= null;
	var $REQ_ESTADO					= null;
	var $REQ_DRU					= null;
	var $REQ_TIPO					= null;
	var $REQ_CLASIFICACION			= null;
	var $REQ_PROVEEDOR				= null;
	var $REQ_GERENCIA				= null;
	var $REQ_NRO_INTERNO			= null;
	var $REQ_FECHA_MODIFICACION		= null;
	var $REQ_USUARIO_APRUEBA		= null;
	var $REQ_FECHA_APRUEBA			= null;
	var $REQ_FASE					= null;
	var $REQ_USUARIO_MODIFICA		= null;
	var $REQ_CAPACIDADES			= null;
	var $REQ_PRIORIDAD_PROV			= null;
	var $REQ_PRIORIDAD_CTE			= null;
	
	var $REQ_AREAS					= array();
	var $REQ_VALORES				= array();
	var $REQ_MODULOS				= array();
	var $REQ_ANEXOS					= array();
	var $REQ_TAREAS					= array();
	var $REQ_ENCARGADOS				= array();
	
	var $RCP_ID						= null;
		
	function __construct( &$_db )
	{
		parent::__construct( '#__gpti_requerimientos', 'req_id', $_db );
	}

	function bind( $datos=array() )
	{
		parent::bind( $datos );

		$this->REQ_FECHA_CREACION	= !isset($datos['REQ_FECHA_CREACION']) && $this->REQ_FECHA_CREACION ? date( "Y-m-d", strtotime( $this->REQ_FECHA_CREACION )) : $this->REQ_FECHA_CREACION;		
		$this->REQ_FECHA_APRUEBA	= !isset($datos['REQ_FECHA_APRUEBA']) && $this->REQ_FECHA_APRUEBA ? date( "Y-m-d", strtotime( $this->REQ_FECHA_APRUEBA )) : $this->REQ_FECHA_APRUEBA;		
		$this->REQ_FECHA_ENTREGA	= !isset($datos['REQ_FECHA_ENTREGA']) && $this->REQ_FECHA_ENTREGA ? date( "Y-m-d", strtotime( $this->REQ_FECHA_ENTREGA )) : $this->REQ_FECHA_ENTREGA;
	}


	function get( $id=0 )
	{
		if( !$id )
			return false;
			
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_REQS_LOAD ( :P_REQ, :C_CURSOR, :C_CURSOR_AREAS, :C_CURSOR_VALORES, :C_CURSOR_MODS, :C_CURSOR_ANEXOS, :C_CURSOR_TAREAS  ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$p_req				= $id;
		oci_bind_by_name( $stmt, ':P_REQ',			$p_req,			40 );
		
		$c_cursor			= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$c_cursor, -1, OCI_B_CURSOR );
		
		$c_cursor_areas		= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR_AREAS', $c_cursor_areas, -1, OCI_B_CURSOR );
		
		$c_cursor_valores	= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR_VALORES', $c_cursor_valores, -1, OCI_B_CURSOR );
		
		$c_cursor_mods		= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR_MODS', 		$c_cursor_mods, -1, OCI_B_CURSOR );
		
		$c_cursor_anexos	= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR_ANEXOS', 		$c_cursor_anexos, -1, OCI_B_CURSOR );
		
		$c_cursor_tareas	= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR_TAREAS', 		$c_cursor_tareas, -1, OCI_B_CURSOR );
		
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
					if( ( $key == 'REQ_PROPOSITO' || $key == 'REQ_DIAGNOSTICO' || $key == 'REQ_CAPACIDADES' ) && is_object( $value ) ) :
						$this->$key		= $value->load();
					else :
						$this->$key		= $value;
					endif;
				endforeach;
				
				if( $this->USUARIO ) :
					unset($user);
					$user 		= clone(JFactory::getUser());
					$user->load( (int)$this->USUARIO );
					$this->USUARIO = $user;
				endif;
				
				if( $this->REQ_USUARIO_APRUEBA ) :
					unset($user);
					$user 		= clone(JFactory::getUser());
					$user->load( (int)$this->REQ_USUARIO_APRUEBA );
					$this->USUARIO_APRUEBA = $user;
				endif;				
				
				if( $this->REQ_USUARIO_MODIFICA ) :
					unset($user);
					$user 		= clone(JFactory::getUser());
					$user->load( (int)$this->REQ_USUARIO_MODIFICA );
					$this->USUARIO_MODIFICA = $user;
				endif;
			endwhile;
			
			//areas	
				$this->REQ_AREAS		= array();
				if( @!oci_execute( $c_cursor_areas ) ) :
					$e 				= oci_error();
					GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
					exit;
				else:
					while ( $rowarea = oci_fetch_assoc( $c_cursor_areas ) ) :
					unset($area);
					$area			= new stdClass();
						foreach( $rowarea as $keyarea => $valuearea ) :
							$area->$keyarea		= $valuearea;
						endforeach;
					$this->REQ_AREAS[]	= $area;
					endwhile;
					oci_free_statement( $c_cursor_areas );
				endif;
			
			//valores	
				$this->REQ_VALORES	= array();
				if( @!oci_execute( $c_cursor_valores ) ) :
					$e 				= oci_error();
					GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
					exit;
				else:
					while ( $rowvalor = oci_fetch_assoc( $c_cursor_valores ) ) :
					unset($valor);
					$valor			= new stdClass();
						foreach( $rowvalor as $keyvalor => $valuevalor ) :
							$valor->$keyvalor		= $valuevalor;
						endforeach;
					$this->REQ_VALORES[]	= $valor;
					endwhile;
					oci_free_statement( $c_cursor_valores );
				endif;
			
			//modulos	
				$this->REQ_MODULOS		= array();
				$this->REQ_ENCARGADOS	= array();
				if( @!oci_execute( $c_cursor_mods ) ) :
					$e 				= oci_error();
					GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
					exit;
				else:
					while ( $rowmod = oci_fetch_assoc( $c_cursor_mods ) ) :
					unset($mod);
					$mod			= new stdClass();
						foreach( $rowmod as $keymod => $valuemod ) :
							$mod->$keymod		= $valuemod;
						endforeach;
				
					if( $mod->ENCARGADO ) :
						unset($user);
						$user 		= clone(JFactory::getUser());
						$user->load( (int)$mod->ENCARGADO );
						$mod->ENCARGADO = $user;
						if( array_search($mod->MOD_ENCARGADO,array_keys($this->REQ_ENCARGADOS)) === false ) :
							$this->REQ_ENCARGADOS[$mod->MOD_ENCARGADO]	= $user;
						endif;
					endif;				
				
					$this->REQ_MODULOS[]	= $mod;
					endwhile;
					oci_free_statement( $c_cursor_mods );
				endif;
			
			//anexos	
				$this->REQ_ANEXOS		= array();
				if( @!oci_execute( $c_cursor_anexos ) ) :
					$e 				= oci_error();
					GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
					exit;
				else:
					while ( $rowanexo = oci_fetch_assoc( $c_cursor_anexos ) ) :
					unset($anexo);
					$anexo			= new stdClass();
						foreach( $rowanexo as $keyanexo => $valueanexo ) :
							$anexo->$keyanexo		= $valueanexo;
						endforeach;
					$anexo->ANX_LINK = '<a href="'.JURI::base()."gpti/anexos/".$this->REQ_ID."/".$anexo->ANX_RUTA.'" title="'.$anexo->ANX_NOMBRE.'" target="_blank">'.$anexo->ANX_NOMBRE.'</a>' ;
					$this->REQ_ANEXOS[]	= $anexo;
					endwhile;
					oci_free_statement( $c_cursor_anexos );
				endif;
			
			//tareas	
				$this->REQ_TAREAS		= array();
				if( @!oci_execute( $c_cursor_tareas ) ) :
					$e 				= oci_error();
					GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
					exit;
				else:
					while ( $rowtarea = oci_fetch_assoc( $c_cursor_tareas ) ) :
					unset($tarea);
					$tarea			= new stdClass();
						foreach( $rowtarea as $keytarea => $valuetarea ) :
							if( ($keytarea == 'TAR_OBSERVACIONES' || $keytarea == 'TAR_OBS_EJECUTOR') && is_object( $valuetarea ) )  :
								$tarea->$keytarea		= $valuetarea->load();
							else :
								$tarea->$keytarea		= $valuetarea;
							endif;
							//$tarea->$keytarea		= $valuetarea;
						endforeach;
				
						unset($user);
						$user 		= clone(JFactory::getUser());
						$user->load( $tarea->CREADOR );
						$tarea->CREADOR = $user;
						
						if( $tarea->RECURSO ) :
							unset($user);
							$user 		= clone(JFactory::getUser());
							$user->load( $tarea->RECURSO );
							$tarea->RECURSO = $user;
						endif;
						
					$this->REQ_TAREAS[]	= $tarea;
					endwhile;
					oci_free_statement( $c_cursor_tareas );
				endif;
				
			oci_free_statement( $c_cursor );
		endif;
		
		return true;
	}
	
	function save()
	{	
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_REQS_SAVE ( :P_ERROR, :P_ID, :P_PROYECTO, :P_NOMBRE, :P_PRIORIDAD, :P_PROPOSITO, :P_FECHA_ENTREGA"
							. ", :P_DIAGNOSTICO, :P_USUARIO, :P_ESTADO, :P_DRU, :P_TIPO, :P_CLASIFICACION, :P_PROVEEDOR, :P_GERENCIA, :P_NRO_INTERNO"
							. ", :P_USUARIO_APRUEBA, :P_FECHA_APRUEBA, :P_FASE, :P_USUARIO_MODIFICA, :P_CAPACIDADES, :P_PRIORIDAD_PROV, :P_PRIORIDAD_CTE ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return;
		endif;
		
		if( !$this->REQ_ID ) :
			$this->REQ_USUARIO = $GPTIuser->USR_ID;
			if( $GPTIuser->USR_PERFIL != 1 ):
				$this->REQ_GERENCIA = $GPTIuser->GER_ID;
			endif;
		endif;
		
		$error			= null;
		oci_bind_by_name( $stmt, ':P_ERROR',			$error,							4000 );
		oci_bind_by_name( $stmt, ':P_ID',				$this->REQ_ID,					40 );
		oci_bind_by_name( $stmt, ':P_PROYECTO',			$this->REQ_PROYECTO,			40 );
		oci_bind_by_name( $stmt, ':P_NOMBRE',			$this->REQ_NOMBRE,				255 );
		oci_bind_by_name( $stmt, ':P_PRIORIDAD',		$this->REQ_PRIORIDAD,			40 );
		
		$clobProposito		= oci_new_descriptor($GPTIconn, OCI_D_LOB);
		oci_bind_by_name( $stmt, ':P_PROPOSITO',		$clobProposito,					-1,	OCI_B_CLOB );
		
		oci_bind_by_name( $stmt, ':P_FECHA_ENTREGA',	$this->REQ_FECHA_ENTREGA,		40 );
		
		$clobDiagnostico	= oci_new_descriptor($GPTIconn, OCI_D_LOB);
		oci_bind_by_name( $stmt, ':P_DIAGNOSTICO',		$clobDiagnostico,				-1,	OCI_B_CLOB );
		
//		$clob = oci_new_descriptor($conn, OCI_D_LOB);
//		oci_bind_by_name( $stmt, ':P_DETALLE',			$clob,		-1,	OCI_B_CLOB );
				
		oci_bind_by_name( $stmt, ':P_USUARIO',			$this->REQ_USUARIO,				40 );
		oci_bind_by_name( $stmt, ':P_ESTADO',			$this->REQ_ESTADO,				40 );
		oci_bind_by_name( $stmt, ':P_DRU',				$this->REQ_DRU,					40 );
		oci_bind_by_name( $stmt, ':P_TIPO',				$this->REQ_TIPO,				40 );
		oci_bind_by_name( $stmt, ':P_CLASIFICACION',	$this->REQ_CLASIFICACION,		40 );
		oci_bind_by_name( $stmt, ':P_PROVEEDOR',		$this->REQ_PROVEEDOR,			40 );
		oci_bind_by_name( $stmt, ':P_GERENCIA',			$this->REQ_GERENCIA,			40 );
		oci_bind_by_name( $stmt, ':P_NRO_INTERNO',		$this->REQ_NRO_INTERNO,			40 );
		oci_bind_by_name( $stmt, ':P_USUARIO_APRUEBA',	$this->REQ_USUARIO_APRUEBA,		40 );
		oci_bind_by_name( $stmt, ':P_FECHA_APRUEBA',	$this->REQ_FECHA_APRUEBA,		40 );
		oci_bind_by_name( $stmt, ':P_FASE',				$this->REQ_FASE,				40 );
		oci_bind_by_name( $stmt, ':P_USUARIO_APRUEBA',	$this->REQ_USUARIO_APRUEBA,		40 );
		oci_bind_by_name( $stmt, ':P_USUARIO_MODIFICA',	$this->REQ_USUARIO_MODIFICA,	40 );
		
		$clobCapacidades	= oci_new_descriptor($GPTIconn, OCI_D_LOB);
		oci_bind_by_name( $stmt, ':P_CAPACIDADES',		$clobCapacidades,				-1,	OCI_B_CLOB );
		
		oci_bind_by_name( $stmt, ':P_PRIORIDAD_PROV',	$this->REQ_PRIORIDAD_PROV,		40 );
		oci_bind_by_name( $stmt, ':P_PRIORIDAD_CTE',	$this->REQ_PRIORIDAD_CTE,		40 );
		
		//if( !oci_execute( $stmt, OCI_DEFAULT ) ) :
		if( !oci_execute( $stmt, OCI_NO_AUTO_COMMIT ) ) :
			oci_rollback($GPTIconn);
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return;
		endif;
		
		$clobProposito->write($this->REQ_PROPOSITO);
		$clobDiagnostico->write($this->REQ_DIAGNOSTICO );
		$clobCapacidades->write($this->REQ_CAPACIDADES );
		
		if( !oci_commit( $GPTIconn ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return;
		endif;
		
		$clobProposito->close();
		$clobDiagnostico->close();
		$clobCapacidades->close();
		
		if( $error ) :
			GPTIHelperError::Raise( htmlentities($error, ENT_QUOTES) );
			return;
		endif;
		
		if( (is_array($this->REQ_MODULOS) && is_object($this->REQ_MODULOS[0])) || (is_array($this->REQ_AREAS) && is_object($this->REQ_AREAS[0])) || (is_array($this->REQ_VALORES) && is_object($this->REQ_VALORES[0])) )
			return true;
		
		if( count( $this->REQ_ANEXOS ) ) :
			$anxs					= $this->REQ_ANEXOS;
			unset( $this->REQ_ANEXOS );
			$this->REQ_ANEXOS		= array();
			foreach( $anxs as $ai => $anx ) :
				$this->REQ_ANEXOS[]		= $anx->ANX_ID;
			endforeach;
		endif;
		
		unset( $anxs );
		$anxs	= $_FILES['REQ_ANEXOS']['name'];
		if( count( $anxs ) ) :
		
			$base	= JPATH_BASE.DS.'gpti'.DS.'anexos'.DS.$this->REQ_ID;
			if( !file_exists($base) ) :
				mkdir($base,0775);
			endif;
		
			foreach( $anxs as $indice => $fname ) :
				$fname = JFilterOutput::stringURLSafe( substr($fname,0,strrchr($fname, ".") ) ) . substr( $fname, strrchr($fname, ".") );
				if( move_uploaded_file( $_FILES['REQ_ANEXOS']['tmp_name'][$indice], $base.DS.$fname ) ) :
					$storeprocedure		= "BEGIN PKG_GPTI.PROC_ANEXO_SAVE ( :P_ERROR, :P_ID, :P_NOMBRE, :P_RUTA ); END;";
					if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
						$e 				= oci_error();
						GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
						return;
					endif;
					
					$error			= null;
					oci_bind_by_name( $stmt, ':P_ERROR',			$error,							4000 );
					$anexoID		= null;
					oci_bind_by_name( $stmt, ':P_ID',				$anexoID,						40 );
					$anexoNombre	= $fname;
					oci_bind_by_name( $stmt, ':P_NOMBRE',			$anexoNombre,					255 );
					$anexoRuta		= $fname;
					oci_bind_by_name( $stmt, ':P_RUTA',				$anexoRuta,						255 );
					
					if( !oci_execute( $stmt ) ) :
						oci_rollback($GPTIconn);
						$e 				= oci_error();
						GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
						return;
					endif;
					
					if( $error ) :
						GPTIHelperError::Raise( htmlentities($error, ENT_QUOTES) );
						return;
					endif;
					
					$this->REQ_ANEXOS[]		= $anexoID;
				endif;
			endforeach;
		endif;

		$storeprocedure		= "BEGIN PKG_GPTI.PROC_REQS_SAVE_DETALLE ( :P_ERROR, :P_ID, :P_AREAS, :P_VALORES, :P_MODS, :P_ANEXOS ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return;
		endif;
		
		/*if( count( $this->REQ_AREAS ) ) :
			$areas							= $this->REQ_AREAS;
			unset( $this->REQ_AREAS );
			$this->REQ_AREAS		= array();
			foreach( $areas as $ai => $area ) :
				$this->REQ_AREAS[]			= $area->ARE_ID;
			endforeach;
		endif;
		
		if( count( $this->REQ_VALORES ) ) :
			$valores				= $this->REQ_VALORES;
			unset( $this->REQ_VALORES );
			$this->REQ_VALORES		= array();
			foreach( $valores as $vi => $valor ) :
				$this->REQ_VALORES[]		= $valor->VAS_ID;
			endforeach;
		endif;
		
		if( count( $this->REQ_MODULOS ) ) :
			$mods					= $this->REQ_MODULOS;
			unset( $this->REQ_MODULOS );
			$this->REQ_MODULOS		= array();
			foreach( $mods as $mi => $mod ) :
				$this->REQ_MODULOS[]		= $mod->MOD_ID;
			endforeach;
		endif;*/
		
		$error			= null;
		oci_bind_by_name( $stmt, ':P_ERROR',			$error,							200 );
		oci_bind_by_name( $stmt, ':P_ID',				$this->REQ_ID,					40 );
		$p_areas		= implode(",", $this->REQ_AREAS);
		oci_bind_by_name( $stmt, ':P_AREAS',			$p_areas,						200 );
		$p_valores		= implode(",", $this->REQ_VALORES);
		oci_bind_by_name( $stmt, ':P_VALORES',			$p_valores,						200 );
		$p_mods			= implode(",", $this->REQ_MODULOS);
		oci_bind_by_name( $stmt, ':P_MODS',				$p_mods,						200 );
		$p_anexos		= implode(",", $this->REQ_ANEXOS);
		oci_bind_by_name( $stmt, ':P_ANEXOS',			$p_anexos,						200 );
	
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
	
	function buscar( $p_orden = null )
	{
			
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_REQS_BUSCAR ( :P_ERROR, :P_ID, :P_PROYECTO, :P_NOMBRE, :P_FECHA_DESDE, :P_FECHA_HASTA, :P_ESTADO, :P_DRU, :P_TIPO, :P_CLASIFICACION, :P_PROVEEDOR, :P_GERENCIA, :P_NRO_INTERNO, :P_USUARIO, :P_FASE, :P_FASE_DESDE, :P_FASE_HASTA, :P_PLAZO, :P_ENCARGADO, :P_ORDEN, :C_CURSOR ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		if( $GPTIuser->USR_PERFIL == 5 || $GPTIuser->USR_PERFIL == 6 ) :
			$p_orden	= "ORDER BY REQS.REQ_PRIORIDAD_PROV ASC, REQS.REQ_FECHA_CREACION DESC";
		endif;
		//$this->REQ_GERENCIA 	= $GPTIuser->USR_GERENCIA;
		//$this->REQ_PROVEEDOR 	= $GPTIuser->USR_PROVEEDOR;

		$error						= null;
		oci_bind_by_name( $stmt, ':P_ERROR',			$error,							2000 );
		oci_bind_by_name( $stmt, ':P_ID',				$this->REQ_ID,					40 );
		oci_bind_by_name( $stmt, ':P_PROYECTO',			$this->REQ_PROYECTO,			40 );
		oci_bind_by_name( $stmt, ':P_NOMBRE',			$this->REQ_NOMBRE,				40 );
		oci_bind_by_name( $stmt, ':P_FECHA_DESDE',		$this->REQ_FECHA_DESDE,			40 );
		oci_bind_by_name( $stmt, ':P_FECHA_HASTA',		$this->REQ_FECHA_HASTA,			40 );
		oci_bind_by_name( $stmt, ':P_ESTADO',			$this->REQ_ESTADO,				40 );
		oci_bind_by_name( $stmt, ':P_DRU',				$this->REQ_DRU,					40 );
		oci_bind_by_name( $stmt, ':P_TIPO',				$this->REQ_TIPO,				40 );
		oci_bind_by_name( $stmt, ':P_CLASIFICACION',	$this->REQ_CLASIFICACION,		40 );
		oci_bind_by_name( $stmt, ':P_PROVEEDOR',		$this->REQ_PROVEEDOR,			40 );
		oci_bind_by_name( $stmt, ':P_GERENCIA',			$this->REQ_GERENCIA,			40 );
		oci_bind_by_name( $stmt, ':P_NRO_INTERNO',		$this->REQ_NRO_INTERNO,			40 );
		oci_bind_by_name( $stmt, ':P_USUARIO',			$this->REQ_USUARIO,				40 );
		oci_bind_by_name( $stmt, ':P_FASE',				$this->REQ_FASE,				40 );
		oci_bind_by_name( $stmt, ':P_FASE_DESDE',		$this->REQ_FASE_DESDE,			40 );
		oci_bind_by_name( $stmt, ':P_FASE_HASTA',		$this->REQ_FASE_HASTA,			40 );
		oci_bind_by_name( $stmt, ':P_PLAZO',			$this->REQ_PLAZO,				40 );
		oci_bind_by_name( $stmt, ':P_ENCARGADO',		$this->REQ_ENCARGADO,			40 );
		oci_bind_by_name( $stmt, ':P_ORDEN',			$p_orden,						1000 );
		
		$c_cursor			= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$c_cursor, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			//echo oci_error(); exit;
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;

		if( $error ) :
			//echo $error; exit;
			GPTIHelperError::Raise( htmlentities($error, ENT_QUOTES) );
			return false;
		endif;
		
		$rows		= array();
		
		if( @!oci_execute( $c_cursor ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return array();
		else:
			while ( $fila = oci_fetch_assoc( $c_cursor ) ) :
			unset($row);
			$row			= new stdClass();
				foreach( $fila as $key => $value ) :
					$row->$key		= $value;
				endforeach;
				$row->REQ_LINK = JRoute::_( "index.php?c=requerimientos&task=ver&REQ_ID=".$row->REQ_ID );
				$row->REQ_DIAS_STATUS		= ($row->REQ_FECHA_PROVEEDOR_TERMINO && strtotime($row->REQ_FECHA_PROVEEDOR_TERMINO)==strtotime($row->REQ_FECHA_PROVEEDOR_INICIO)) ? 1 : (int) ceil( (strtotime($row->REQ_FECHA_PROVEEDOR_TERMINO)-strtotime($row->REQ_FECHA_PROVEEDOR_INICIO)) / (60 * 60 * 24) );

				$row->REQ_ENCARGADOS	= array();
				
				$sp_encargados		= "BEGIN PKG_GPTI.PROC_REQS_ENCARGADOS ( :P_ID, :C_CURSOR ); END;";
				if( !$stmt_encargados = oci_parse( $GPTIconn, $sp_encargados ) ) :
					$e 				= oci_error();
					GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
					return false;
				endif;
				
				oci_bind_by_name( $stmt_encargados, ':P_ID', 		$row->REQ_ID,		40 );
				
				$cur_encargados		= oci_new_cursor( $GPTIconn );
				oci_bind_by_name( $stmt_encargados, ':C_CURSOR', 	$cur_encargados, -1, OCI_B_CURSOR );
		
				if( !oci_execute( $stmt_encargados ) ) :
					$e 				= oci_error();
					GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
					return false;
				endif;

				if( @!oci_execute( $cur_encargados ) ) :
					$e 				= oci_error();
					GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
					exit;
				else:
					while ( $rowmod = oci_fetch_assoc( $cur_encargados ) ) :
					unset($mod);
					$mod			= new stdClass();
						foreach( $rowmod as $keymod => $valuemod ) :
							$mod->$keymod		= $valuemod;
						endforeach;
				
					if( $mod->ENCARGADO ) :
						unset($user);
						$user 		= clone(JFactory::getUser());
						$user->load( (int)$mod->ENCARGADO );
						$mod->ENCARGADO = $user;
						if( array_search($mod->MOD_ENCARGADO,array_keys($row->REQ_ENCARGADOS)) === false ) :
							$row->REQ_ENCARGADOS[$mod->MOD_ENCARGADO]	= $user;
						endif;
					endif;
					
					endwhile;
					oci_free_statement( $cur_encargados );
				endif;
				
				if( $GPTIuser->USR_PERFIL == 5 || $GPTIuser->USR_PERFIL == 6 ) :
					$row->REQ_PRIORIDAD		= $row->REQ_PRIORIDAD_PROV;
				elseif( $this->REQ_PLAZO == 3 ) :
					$row->REQ_PRIORIDAD		= $row->REQ_PRIORIDAD_CTE;
				endif;

			$rows[]			= $row;
			endwhile;
			oci_free_statement( $c_cursor );
		endif;
		
		return $rows;
	}
	
	function getTablaDecision()
	{
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_TABLAS_INDICE_DECISION ( :C_CURSOR_AREAS, :C_CURSOR_VALORES, :C_CURSOR_TABLA  ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$c_cursor_areas		= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR_AREAS', $c_cursor_areas, -1, OCI_B_CURSOR );
		
		$c_cursor_valores	= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR_VALORES', $c_cursor_valores, -1, OCI_B_CURSOR );
		
		$c_cursor_tabla		= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR_TABLA', 		$c_cursor_tabla, -1, OCI_B_CURSOR );

		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$tabla				= array();
		$tabla['areas']		= array();
		$tabla['valores']	= array();
		$tabla['tabla']		= array();
		
		if( @!oci_execute( $c_cursor_areas ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $row = oci_fetch_assoc( $c_cursor_areas ) ) :
				unset($area);
				$area				= new stdClass();
				foreach( $row as $key => $value ) :
					$area->$key		= $value;
				endforeach;
				$tabla['areas'][]	= $area;
			endwhile;
			oci_free_statement( $c_cursor_areas );
		endif;
		
		if( @!oci_execute( $c_cursor_valores ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $row = oci_fetch_assoc( $c_cursor_valores ) ) :
				unset($valor);
				$valor				= new stdClass();
				foreach( $row as $key => $value ) :
					$valor->$key		= $value;
				endforeach;
				$tabla['valores'][]	= $valor;
			endwhile;
			oci_free_statement( $c_cursor_valores );
		endif;
		
		if( @!oci_execute( $c_cursor_tabla ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $row = oci_fetch_assoc( $c_cursor_tabla ) ) :
				unset($tt);
				$tt				= new stdClass();
				foreach( $row as $key => $value ) :
					$tt->$key		= $value;
				endforeach;
				$tabla['tabla'][]	= $tt;
			endwhile;
			oci_free_statement( $c_cursor_tabla );
		endif;
		
		return $tabla;
	}
	
	function cambioprioridad( $p_id=0 )
	{
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_REQ_CP_LOAD ( :P_ID, :C_CURSOR  ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		oci_bind_by_name( $stmt, ':P_ID', $p_id, 4000 );
		
		$c_cursor			= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', $c_cursor, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		$rows				= array();
		
		if( @!oci_execute( $c_cursor ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		else:
			while ( $row = oci_fetch_assoc( $c_cursor ) ) :
				unset($obj);
				$obj				= new stdClass();
				foreach( $row as $key => $value ) :
					if( ( $key == 'RCP_OBS' || $key == 'RCP_OBS_ADMIN' ) && is_object( $value ) ) :
						$obj->$key		= $value->load();
					else :
						$obj->$key		= $value;
					endif;
				endforeach;
				$rows[]	= $obj;
			endwhile;
			oci_free_statement( $c_cursor );
		endif;			
		
		return $rows;
	}
	
	function cambioprioridades()
	{
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_REQ_CP_LISTA ( :P_GERENCIA, :C_CURSOR  ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		$p_gerencia			= $GPTIuser->USR_GERENCIA;
		oci_bind_by_name( $stmt, ':P_GERENCIA',			$p_gerencia,					4000 );
		
		$c_cursor			= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', $c_cursor, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		$rows				= array();
		
		if( @!oci_execute( $c_cursor ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		else:
			while ( $row = oci_fetch_assoc( $c_cursor ) ) :
				unset($obj);
				$obj				= new stdClass();
				foreach( $row as $key => $value ) :
					if( ( $key == 'RCP_OBS' || $key == 'RCP_OBS_ADMIN' ) && is_object( $value ) ) :
						$obj->$key		= $value->load();
					else :
						$obj->$key		= $value;
					endif;
				endforeach;
				$rows[]	= $obj;
			endwhile;
			oci_free_statement( $c_cursor );
		endif;			
		
		return $rows;
	}

	function cambioprioridadesAdmin()
	{
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_REQ_CP_LISTA ( :P_GERENCIA, :C_CURSOR  ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		$p_gerencia			= null;
		oci_bind_by_name( $stmt, ':P_GERENCIA',			$p_gerencia,					40 );
		
		$c_cursor			= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', $c_cursor, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		$rows				= array();
		
		if( @!oci_execute( $c_cursor ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		else:
			while ( $row = oci_fetch_assoc( $c_cursor ) ) :
				unset($obj);
				$obj				= new stdClass();
				foreach( $row as $key => $value ) :
					$obj->$key		= $value;
				endforeach;
				$rows[]	= $obj;
			endwhile;
			oci_free_statement( $c_cursor );
		endif;			
		
		return $rows;
	}
	
	function cambioprioridades_ingresar($p_orden='')
	{
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_REQ_CP_SAVE( :P_ERROR, :P_ID, :P_GERENCIA, :P_PROVEEDOR, :P_USUARIO, :P_ESTADO, :P_OBS, :P_OBS_ADMIN, :P_ORDEN ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		$error						= null;
		oci_bind_by_name( $stmt, ':P_ERROR',			$error,							1000 );
		$p_id						= null;
		oci_bind_by_name( $stmt, ':P_ID',				$p_id,							40 );
		$p_gerencia					= $GPTIuser->USR_GERENCIA;
		oci_bind_by_name( $stmt, ':P_GERENCIA',			$p_gerencia,					40 );
		$p_proveedor				= null;
		oci_bind_by_name( $stmt, ':P_PROVEEDOR',		$p_proveedor,					40 );
		$p_usuario					= $GPTIuser->USR_ID;
		oci_bind_by_name( $stmt, ':P_USUARIO',			$p_usuario,						40 );
		$p_estado					= 'I';
		oci_bind_by_name( $stmt, ':P_ESTADO',			$p_estado,						1 );
		
		$clobObs					= oci_new_descriptor($GPTIconn, OCI_D_LOB);
		oci_bind_by_name( $stmt, ':P_OBS',		$clobObs,		-1,	OCI_B_CLOB );
		$clobObsAdmin				= oci_new_descriptor($GPTIconn, OCI_D_LOB);
		oci_bind_by_name( $stmt, ':P_OBS_ADMIN', $clobObsAdmin,	-1,	OCI_B_CLOB );
		
		oci_bind_by_name( $stmt, ':P_ORDEN',			$p_orden,						2000 );
		
		if( !oci_execute( $stmt, OCI_NO_AUTO_COMMIT ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		$clobObs->write($_POST['RCP_OBS']);
		$clobObsAdmin->write($_POST['RCP_OBS_ADMIN']);
		
		if( !oci_commit( $GPTIconn ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		$clobObs->close();
		$clobObsAdmin->close();
		
		if( $error ) :
			GPTIHelperError::Raise( htmlentities($error, ENT_QUOTES) );
			return false;
		endif;
		
		$this->RCP_ID		= $p_id;
		
		return true;
	}
	
	function cambioprioridades_ingresar_prov($p_orden='', $p_proveedor=null)
	{
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_REQ_CP_SAVE( :P_ERROR, :P_ID, :P_GERENCIA, :P_PROVEEDOR, :P_USUARIO, :P_ESTADO, :P_OBS, :P_OBS_ADMIN, :P_ORDEN ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		$error						= null;
		oci_bind_by_name( $stmt, ':P_ERROR',			$error,							1000 );
		$p_id						= null;
		oci_bind_by_name( $stmt, ':P_ID',				$p_id,							40 );
		$p_gerencia					= null;
		oci_bind_by_name( $stmt, ':P_GERENCIA',			$p_gerencia,					40 );
		oci_bind_by_name( $stmt, ':P_PROVEEDOR',		$p_proveedor,					40 );
		$p_usuario					= $GPTIuser->USR_ID;
		oci_bind_by_name( $stmt, ':P_USUARIO',			$p_usuario,						40 );
		$p_estado					= 'A';
		oci_bind_by_name( $stmt, ':P_ESTADO',			$p_estado,						1 );
		
		$clobObs					= oci_new_descriptor($GPTIconn, OCI_D_LOB);
		oci_bind_by_name( $stmt, ':P_OBS',		$clobObs,		-1,	OCI_B_CLOB );
		$clobObsAdmin				= oci_new_descriptor($GPTIconn, OCI_D_LOB);
		oci_bind_by_name( $stmt, ':P_OBS_ADMIN', $clobObsAdmin,	-1,	OCI_B_CLOB );
		
		oci_bind_by_name( $stmt, ':P_ORDEN',			$p_orden,						2000 );
		
		if( !oci_execute( $stmt, OCI_NO_AUTO_COMMIT ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		$clobObs->write($_POST['RCP_OBS']);
		$clobObsAdmin->write($_POST['RCP_OBS_ADMIN']);
		
		if( !oci_commit( $GPTIconn ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		$clobObs->close();
		$clobObsAdmin->close();
		
		if( $error ) :
			GPTIHelperError::Raise( htmlentities($error, ENT_QUOTES) );
			return false;
		endif;
		
		$this->RCP_ID		= $p_id;
				
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_REQ_CP_APROBAR( :P_ID, :P_ESTADO, :P_OBS_ADMIN ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		oci_bind_by_name( $stmt, ':P_ID',				$p_id,							40 );
		oci_bind_by_name( $stmt, ':P_ESTADO',			$p_estado,						1 );
		
		$clobObsAdmin				= oci_new_descriptor($GPTIconn, OCI_D_LOB);
		oci_bind_by_name( $stmt, ':P_OBS_ADMIN', $clobObsAdmin,	-1,	OCI_B_CLOB );
		
		if( !oci_execute( $stmt, OCI_NO_AUTO_COMMIT ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		$clobObsAdmin->write($_POST['RCP_OBS_ADMIN']);
		
		if( !oci_commit( $GPTIconn ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		$clobObsAdmin->close();
		
		return true;
	}
	
	function cambioprioridades_ingresar_cte($p_orden='')
	{
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_REQ_CP_SAVE( :P_ERROR, :P_ID, :P_GERENCIA, :P_PROVEEDOR, :P_USUARIO, :P_ESTADO, :P_OBS, :P_OBS_ADMIN, :P_ORDEN ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		$error						= null;
		oci_bind_by_name( $stmt, ':P_ERROR',			$error,							1000 );
		$p_id						= null;
		oci_bind_by_name( $stmt, ':P_ID',				$p_id,							40 );
		$p_gerencia					= null;
		oci_bind_by_name( $stmt, ':P_GERENCIA',			$p_gerencia,					40 );
		$p_proveedor				= null;
		oci_bind_by_name( $stmt, ':P_PROVEEDOR',		$p_proveedor,					40 );
		$p_usuario					= $GPTIuser->USR_ID;
		oci_bind_by_name( $stmt, ':P_USUARIO',			$p_usuario,						40 );
		$p_estado					= 'A';
		oci_bind_by_name( $stmt, ':P_ESTADO',			$p_estado,						1 );
		
		$clobObs					= oci_new_descriptor($GPTIconn, OCI_D_LOB);
		oci_bind_by_name( $stmt, ':P_OBS',		$clobObs,		-1,	OCI_B_CLOB );
		$clobObsAdmin				= oci_new_descriptor($GPTIconn, OCI_D_LOB);
		oci_bind_by_name( $stmt, ':P_OBS_ADMIN', $clobObsAdmin,	-1,	OCI_B_CLOB );
		
		oci_bind_by_name( $stmt, ':P_ORDEN',			$p_orden,						2000 );
		
		if( !oci_execute( $stmt, OCI_NO_AUTO_COMMIT ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		$clobObs->write($_POST['RCP_OBS']);
		$clobObsAdmin->write($_POST['RCP_OBS_ADMIN']);
		
		if( !oci_commit( $GPTIconn ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		$this->RCP_ID		= $p_id;
		
		$clobObs->close();
		$clobObsAdmin->close();
		
		if( $error ) :
			GPTIHelperError::Raise( htmlentities($error, ENT_QUOTES) );
			return false;
		endif;
		
				
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_REQ_CP_APROBAR( :P_ID, :P_ESTADO, :P_OBS_ADMIN ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		oci_bind_by_name( $stmt, ':P_ID',				$p_id,							40 );
		oci_bind_by_name( $stmt, ':P_ESTADO',			$p_estado,						1 );
		
		$clobObsAdmin				= oci_new_descriptor($GPTIconn, OCI_D_LOB);
		oci_bind_by_name( $stmt, ':P_OBS_ADMIN', $clobObsAdmin,	-1,	OCI_B_CLOB );
		
		if( !oci_execute( $stmt, OCI_NO_AUTO_COMMIT ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		$clobObsAdmin->write($_POST['RCP_OBS_ADMIN']);
		
		if( !oci_commit( $GPTIconn ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		$clobObsAdmin->close();
		
		return true;
	}
	
	function cambiosprioridad_aprobar( $p_id, $p_estado )
	{
		//echo "p_id=$p_id<br />p_estado=$p_estado"; exit;
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_REQ_CP_APROBAR( :P_ID, :P_ESTADO, :P_OBS_ADMIN ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
//		$error						= null;
//		oci_bind_by_name( $stmt, ':P_ERROR',			$error,							1000 );

		oci_bind_by_name( $stmt, ':P_ID',				$p_id,							40 );
		oci_bind_by_name( $stmt, ':P_ESTADO',			$p_estado,						1 );
		
		$clobObsAdmin				= oci_new_descriptor($GPTIconn, OCI_D_LOB);
		oci_bind_by_name( $stmt, ':P_OBS_ADMIN', $clobObsAdmin,	-1,	OCI_B_CLOB );
		
		if( !oci_execute( $stmt, OCI_NO_AUTO_COMMIT ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		$clobObsAdmin->write($_POST['RCP_OBS_ADMIN']);
		
		if( !oci_commit( $GPTIconn ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		$clobObsAdmin->close();
		
//		if( $error ) :
//			GPTIHelperError::Raise( htmlentities($error, ENT_QUOTES) );
//			return false;
//		endif;
		
		return true;
	}
	
	function getReporteDesarrollo()
	{
		$r					= new stdClass();
		$r->label			= 'CARTERA DE PROYECTOS EN DESARROLLO';
		$r->trabajo			= 0;
		$r->duracion		= 0;
		$r->inicio			= 0;
		$r->termino			= 0;
		$r->detalle			= array();
		$r->proveedores		= array();

		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_REPORTE_PROVEEDORES ( :C_CURSOR ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$cur_proveedores	= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$cur_proveedores, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		if( @!oci_execute( $cur_proveedores ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $row = oci_fetch_assoc( $cur_proveedores ) ) :
				if( array_search($row['PRO_ID'],array_keys($r->proveedores)) === false ) :
					unset($proveedor);
					$proveedor				= new stdClass;
					$proveedor->id			= $row['PRO_ID'];
					$proveedor->nombre		= $row['PRO_NOMBRE'];
					unset($user);
					$user 		= clone(JFactory::getUser());
					$user->load( (int)$row['GERENTE'] );

					$proveedor->gerentes	= $user->get('name');
				else :
					unset($proveedor);
					$proveedor				= $r->proveedores[$row['PRO_ID']];
					
					unset($user);
					$user 		= clone(JFactory::getUser());
					$user->load( (int)$row['GERENTE'] );
					$proveedor->gerentes	.= ', '.$user->get('name');
				endif;
				
				$proveedor->label				= $proveedor->nombre . ' (' . $proveedor->gerentes . ')';
				$proveedor->trabajo				= 0;
				$proveedor->duracion			= 0;
				$proveedor->inicio				= 0;
				$proveedor->termino				= 0;
				$proveedor->detalle				= array();
				$proveedor->reqs				= array();
				$r->proveedores[$row['PRO_ID']]	= $proveedor;
			endwhile;
			oci_free_statement( $cur_proveedores );
			
			if( !count($r->proveedores) )
				return false;
				
			foreach( $r->proveedores as $index => $proveedor ) :
			
				$sp_reqs			= "BEGIN PKG_GPTI.PROC_REPORTE_REQS_DESA( :P_PROVEEDOR, :C_CURSOR ); END;";
				if( !$stmt_reqs		= oci_parse( $GPTIconn, $sp_reqs ) ) :
					$e 				= oci_error();
					GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
					exit;
				endif;
				
				oci_bind_by_name( $stmt_reqs, ':P_PROVEEDOR', 		$proveedor->id, 	40 );
				$cur_reqs			= oci_new_cursor( $GPTIconn );
				oci_bind_by_name( $stmt_reqs, ':C_CURSOR', 			$cur_reqs, 	-1, OCI_B_CURSOR );
				
				if( !oci_execute( $stmt_reqs ) ) :
					$e 				= oci_error();
					GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
					exit;
				endif;
				
				if( @!oci_execute( $cur_reqs ) ) :
					$e 				= oci_error();
					GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
					exit;
				else:
					while ( $row_reqs = oci_fetch_assoc( $cur_reqs ) ) :
					
						$req				= new stdClass();
						$req->id			= $row_reqs['REQ_ID'];
						$req->nombre		= $row_reqs['REQ_NOMBRE'];
						$req->label			= $req->nombre;
						$req->trabajo		= 0;
						$req->duracion		= 0;
						$req->inicio		= 0;
						$req->termino		= 0;
						$req->detalle		= array();
					
						$sp_tareas			= "BEGIN PKG_GPTI.PROC_REPORTE_TAREAS( :P_REQ, :C_CURSOR ); END;";
						if( !$stmt_tareas	= oci_parse( $GPTIconn, $sp_tareas ) ) :
							$e 				= oci_error();
							GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
							exit;
						endif;
						
						oci_bind_by_name( $stmt_tareas, ':P_REQ', 			$req->id, 	40 );
						$cur_tareas			= oci_new_cursor( $GPTIconn );
						oci_bind_by_name( $stmt_tareas, ':C_CURSOR', 		$cur_tareas, -1, OCI_B_CURSOR );
						
						if( !oci_execute( $stmt_tareas ) ) :
							$e 				= oci_error();
							GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
							exit;
						endif;
						
						if( @!oci_execute( $cur_tareas ) ) :
							$e 				= oci_error();
							GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
							exit;
						else:
							while ( $row_tareas = oci_fetch_assoc( $cur_tareas ) ) :
							
								$hh					= (int) $row_tareas['TAR_HH_ESTIMADA'];
								$inicio				= strtotime($row_tareas['TAR_FECHA_INICIO']);
								$termino			= strtotime($row_tareas['TAR_FECHA_TERMINO']);
								
								$req->trabajo		+= $hh;
								$proveedor->trabajo	+= $hh;
								$r->trabajo			+= $hh;
								
								$dias					= (int) floor( ($termino-$inicio) / (60 * 60 * 24) );
								
								$req->duracion			+= $dias;
								$proveedor->duracion	+= $dias;
								$r->duracion			+= $dias;
								
								$hhdia					= !$dias ? $hh : ceil($hh/$dias);

								if( !$dias ) :
									$req->detalle[date('n',$inicio)-1]		+= $hhdia;
									$proveedor->detalle[date('n',$inicio)-1]	+= $hhdia;
									$r->detalle[date('n',$inicio)-1]			+= $hhdia;
								else :
									$suma = 0;
									$proxanno = false;
									for( $hoy=0; $hoy < $dias; ++$hoy) :
										$mes						= $hoy ? date('n',strtotime("+$hoy day",$inicio)) : date('n',$inicio);
										$anno						= $hoy ? date('Y',strtotime("+$hoy day",$inicio)) : date('Y',$inicio);
										if( $anno != date('Y') ) :
											$proxanno = true;
											continue;
										endif;
										$req->detalle[$mes-1]		+= $hhdia;
										$proveedor->detalle[$mes-1]	+= $hhdia;
										$r->detalle[$mes-1]			+= $hhdia;
										$suma						+= $hhdia;
										$ultimomes					= $mes-1;
									endfor;
									if( $suma < $hh && !$proxanno ) :
										$req->detalle[$ultimomes]		+= $hh - $suma;
										$proveedor->detalle[$ultimomes]	+= $hh - $suma;
										$r->detalle[$ultimomes]			+= $hh - $suma;
									elseif( $suma > $hh ) :
										$req->detalle[$ultimomes]		-= $suma - $hh;
										$proveedor->detalle[$ultimomes]	-= $suma - $hh;
										$r->detalle[$ultimomes]			-= $suma - $hh;
									endif;
								endif;
								//exit;
								
								$req->inicio		= !$req->inicio || $inicio < $req->inicio ? $inicio : $req->inicio;
								$proveedor->inicio	= !$proveedor->inicio || $inicio < $proveedor->inicio ? $inicio : $proveedor->inicio;
								$r->inicio			= !$r->inicio || $inicio < $r->inicio ? $inicio : $r->inicio;
								
								$req->termino		= !$req->termino || $termino > $req->termino ? $termino : $req->termino;
								$proveedor->termino	= !$proveedor->termino || $termino > $proveedor->termino ? $termino : $proveedor->termino;
								$r->termino			= !$r->termino || $termino > $r->termino ? $termino : $r->termino;
							
							endwhile;
							oci_free_statement( $cur_tareas );
						endif;
						
						$r->proveedores[$index]->reqs[]			= $req;
					
					endwhile;
					oci_free_statement( $cur_reqs );
				endif;
			
			endforeach;
			
		endif;
		
		return $r;
		
	}
	
	function getReporteEspera()
	{
		$r					= new stdClass();
		$r->label			= 'CARTERA DE PROYECTOS EN ESPERA';
		$r->trabajo			= 0;
		$r->duracion		= 0;
		$r->inicio			= 0;
		$r->termino			= 0;
		//$r->detalle			= array();
		$r->proveedores		= array();

		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_REPORTE_PROVEEDORES ( :C_CURSOR ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$cur_proveedores	= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$cur_proveedores, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		if( @!oci_execute( $cur_proveedores ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $row = oci_fetch_assoc( $cur_proveedores ) ) :
				if( array_search($row['PRO_ID'],array_keys($r->proveedores)) === false ) :
					unset($proveedor);
					$proveedor				= new stdClass;
					$proveedor->id			= $row['PRO_ID'];
					$proveedor->nombre		= $row['PRO_NOMBRE'];
					unset($user);
					$user 		= clone(JFactory::getUser());
					$user->load( (int)$row['GERENTE'] );

					$proveedor->gerentes	= $user->get('name');
				else :
					unset($proveedor);
					$proveedor				= $r->proveedores[$row['PRO_ID']];
					
					unset($user);
					$user 		= clone(JFactory::getUser());
					$user->load( (int)$row['GERENTE'] );
					$proveedor->gerentes	.= ', '.$user->get('name');
				endif;
				
				$proveedor->label				= $proveedor->nombre . ' (' . $proveedor->gerentes . ')';
				$proveedor->trabajo				= 0;
				$proveedor->duracion			= 0;
				$proveedor->inicio				= 0;
				$proveedor->termino				= 0;
				//$proveedor->detalle				= array();
				$proveedor->reqs				= array();
				$r->proveedores[$row['PRO_ID']]	= $proveedor;
			endwhile;
			oci_free_statement( $cur_proveedores );
			
			if( !count($r->proveedores) )
				return false;
				
			foreach( $r->proveedores as $index => $proveedor ) :
			
				$sp_reqs			= "BEGIN PKG_GPTI.PROC_REPORTE_REQS_DESA( :P_PROVEEDOR, :C_CURSOR ); END;";
				if( !$stmt_reqs		= oci_parse( $GPTIconn, $sp_reqs ) ) :
					$e 				= oci_error();
					GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
					exit;
				endif;
				
				oci_bind_by_name( $stmt_reqs, ':P_PROVEEDOR', 		$proveedor->id, 	40 );
				$cur_reqs			= oci_new_cursor( $GPTIconn );
				oci_bind_by_name( $stmt_reqs, ':C_CURSOR', 			$cur_reqs, 	-1, OCI_B_CURSOR );
				
				if( !oci_execute( $stmt_reqs ) ) :
					$e 				= oci_error();
					GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
					exit;
				endif;
				
				if( @!oci_execute( $cur_reqs ) ) :
					$e 				= oci_error();
					GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
					exit;
				else:
					while ( $row_reqs = oci_fetch_assoc( $cur_reqs ) ) :
					
						$req				= new stdClass();
						$req->id			= $row_reqs['REQ_ID'];
						$req->nombre		= $row_reqs['REQ_NOMBRE'];
						$req->label			= $req->nombre;
						$req->trabajo		= 0;
						$req->duracion		= 0;
						$req->inicio		= 0;
						$req->termino		= 0;
						$req->detalle		= array();
					
						$sp_tareas			= "BEGIN PKG_GPTI.PROC_REPORTE_TAREAS( :P_REQ, :C_CURSOR ); END;";
						if( !$stmt_tareas	= oci_parse( $GPTIconn, $sp_tareas ) ) :
							$e 				= oci_error();
							GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
							exit;
						endif;
						
						oci_bind_by_name( $stmt_tareas, ':P_REQ', 			$req->id, 	40 );
						$cur_tareas			= oci_new_cursor( $GPTIconn );
						oci_bind_by_name( $stmt_tareas, ':C_CURSOR', 		$cur_tareas, -1, OCI_B_CURSOR );
						
						if( !oci_execute( $stmt_tareas ) ) :
							$e 				= oci_error();
							GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
							exit;
						endif;
						
						if( @!oci_execute( $cur_tareas ) ) :
							$e 				= oci_error();
							GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
							exit;
						else:
							while ( $row_tareas = oci_fetch_assoc( $cur_tareas ) ) :
							
								$hh					= (int) $row_tareas['TAR_HH_ESTIMADA'];
								$inicio				= strtotime($row_tareas['TAR_FECHA_INICIO']);
								$termino			= strtotime($row_tareas['TAR_FECHA_TERMINO']);
								
								$req->trabajo		+= $hh;
								$proveedor->trabajo	+= $hh;
								$r->trabajo			+= $hh;
								
								$dias					= (int) floor( ($termino-$inicio) / (60 * 60 * 24) );
								
								$req->duracion			+= $dias;
								$proveedor->duracion	+= $dias;
								$r->duracion			+= $dias;
								
								/*$hhdia					= !$dias ? $hh : ceil($hh/$dias);

								if( !$dias ) :
									$req->detalle[date('n',$inicio)-1]		+= $hhdia;
									$proveedor->detalle[date('n',$inicio)-1]	+= $hhdia;
									$r->detalle[date('n',$inicio)-1]			+= $hhdia;
								else :
									$suma = 0;
									$proxanno = false;
									for( $hoy=0; $hoy < $dias; ++$hoy) :
										$mes						= $hoy ? date('n',strtotime("+$hoy day",$inicio)) : date('n',$inicio);
										$anno						= $hoy ? date('Y',strtotime("+$hoy day",$inicio)) : date('Y',$inicio);
										if( $anno != date('Y') ) :
											$proxanno = true;
											continue;
										endif;
										$req->detalle[$mes-1]		+= $hhdia;
										$proveedor->detalle[$mes-1]	+= $hhdia;
										$r->detalle[$mes-1]			+= $hhdia;
										$suma						+= $hhdia;
										$ultimomes					= $mes-1;
									endfor;
									if( $suma < $hh && !$proxanno ) :
										$req->detalle[$ultimomes]		+= $hh - $suma;
										$proveedor->detalle[$ultimomes]	+= $hh - $suma;
										$r->detalle[$ultimomes]			+= $hh - $suma;
									elseif( $suma > $hh ) :
										$req->detalle[$ultimomes]		-= $suma - $hh;
										$proveedor->detalle[$ultimomes]	-= $suma - $hh;
										$r->detalle[$ultimomes]			-= $suma - $hh;
									endif;
								endif;*/
								
								$req->inicio		= !$req->inicio || $inicio < $req->inicio ? $inicio : $req->inicio;
								$proveedor->inicio	= !$proveedor->inicio || $inicio < $proveedor->inicio ? $inicio : $proveedor->inicio;
								$r->inicio			= !$r->inicio || $inicio < $r->inicio ? $inicio : $r->inicio;
								
								$req->termino		= !$req->termino || $termino > $req->termino ? $termino : $req->termino;
								$proveedor->termino	= !$proveedor->termino || $termino > $proveedor->termino ? $termino : $proveedor->termino;
								$r->termino			= !$r->termino || $termino > $r->termino ? $termino : $r->termino;
							
							endwhile;
							oci_free_statement( $cur_tareas );
						endif;
						
						$maxduracion				= ($req->duracion*2) > 730-((int)date("z",$req->inicio)*2) ? 730-((int)date("z",$req->inicio)*2) : ($req->duracion*2);
						$req->detalle				= '<div class="tiempo" style="margin-left:'.((int)date("z",$req->inicio)*2).'px; width:'.$maxduracion.'px;"></div>';
						
						$r->proveedores[$index]->reqs[]			= $req;
					
					endwhile;
					oci_free_statement( $cur_reqs );
				endif;
			
				$maxduracion						= ($r->proveedores[$index]->duracion*2) > 730-((int)date("z",$r->proveedores[$index]->inicio)*2) ? 730-((int)date("z",$r->proveedores[$index]->inicio)*2) : ($r->proveedores[$index]->duracion*2);
				$r->proveedores[$index]->detalle	= '<div class="tiempo" style="margin-left:'.((int)date("z",$r->proveedores[$index]->inicio)*2).'px; width:'.$maxduracion.'px;"></div>';
			
			endforeach;
			
		endif;
		
		$maxduracion		= ($r->duracion*2) > 730-((int)date("z",$r->inicio)*2) ? 730-((int)date("z",$r->inicio)*2) : ($r->duracion*2);
		$r->detalle			= '<div class="tiempo" style="margin-left:'.((int)date("z",$r->inicio)*2).'px; width:'.$maxduracion.'px;"></div>';
		
		return $r;
		
	}
	
}
?>
