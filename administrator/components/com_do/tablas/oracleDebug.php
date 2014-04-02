<?php
/**
 * @version		$Id: medisyn.php 2010-08-07 sgarcia $
 * @package		Joomla
 * @subpackage	DO
 * @autor		Diseño Objetivo wwww.do.cl
 * @copyright	Copyright (C) 2006 - 2010 Diseño Objetivo. Todos los derechos reservados.
 * @license		LICENCIA_DO.php
 */

	/**************************************/
	/*                                    */
	/*          Diseño Objetivo           */
	/*       www.do.cl / info@do.cl       */
	/*      Fono: (56-02) 228 13 91       */
	/*                                    */
	/**************************************/
	
// sin acceso directo
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * @package		Joomla
 * @subpackage	DO
 */
class DOOracleDebug extends JTable
{

	private		$_link;
	public		$_error;

	function __construct( &$_db )
	{
		parent::__construct( '', '', $_db );
		$session		=& JFactory::getSession();
		$DO_oci8_link	=& $session->get( 'DO_oci8_link', null );
		if( $DO_oci8_link && is_resource( $DO_oci8_link ) ) :
			$this->_link = $DO_oci8_link;
		else :
			$this->_error	= $this->errores(0);
			return false;
		endif;
	}

	function conexion()
	{
		if( !$this->_link || !is_resource( $this->_link ) ) :
			$this->_error	= $this->errores(0);
			return false;
		endif;
		return true;
	}
	
	function funcionario( $rut=null )
	{
		echo "\nfuncionario( $rut )";
		if( !$this->conexion() ) :
			return false;
		endif;
		
		if( !$rut || !is_numeric( $rut ) ) :
			$this->_error	= $this->errores(4);
			return false;
		endif;
		
		$storeprocedure		= "BEGIN"
							." MEDISYN_1.CAC_INTRANET_PKG.PROC_CONSULTA_FUNCIONARIO"
							." ( :N_RUT_FUNCIONARIO, :NOMBRES, :APELLIDOS, :EMAIL, :FECHA_NACIMIENTO, :CARGO, :UNIDAD, :SUPERIOR, :ANEXO, :C_ENCONTRADO, :V_MENSAJE, :C_SUBALTERNOS );"
							." COMMIT; END;"
							;
		echo "\nstoreprocedure = \n$storeprocedure";
		$stmt				= oci_parse( $this->_link, $storeprocedure );
		
		$funcionario		= new stdClass;
		$funcionario->rut	= (int)$rut;
		
		oci_bind_by_name( $stmt, ':N_RUT_FUNCIONARIO',	$funcionario->rut,			32 );
		oci_bind_by_name( $stmt, ':NOMBRES', 			$funcionario->nombres,		40 );
		oci_bind_by_name( $stmt, ':APELLIDOS',			$funcionario->apellidos,	40 );
		oci_bind_by_name( $stmt, ':EMAIL', 				$funcionario->email,		40 );
		oci_bind_by_name( $stmt, ':FECHA_NACIMIENTO',	$funcionario->fechanac,		32 );
		oci_bind_by_name( $stmt, ':CARGO',				$funcionario->cargo,		80 );
		oci_bind_by_name( $stmt, ':UNIDAD',				$funcionario->unidad,		80 );
		oci_bind_by_name( $stmt, ':SUPERIOR',			$funcionario->superior,		32 );
		oci_bind_by_name( $stmt, ':ANEXO',				$funcionario->anexo,		32 );
		
		oci_bind_by_name( $stmt,':C_ENCONTRADO',		$_encontrados,				200 );
		oci_bind_by_name( $stmt,':V_MENSAJE',			$_mensaje,					200 );
		
		$subalternos	= oci_new_cursor( $this->_link );
		oci_bind_by_name( $stmt, ':C_SUBALTERNOS', 		$subalternos, -1, OCI_B_CURSOR );
		
		echo "\nexecute";
		if( !oci_execute( $stmt ) ) :
			$this->_error	= $this->errores(3);
			return false;
		endif;

		//if( $_encontrados == 'S' || $_encontrados == 'S ' ) :
		if( ereg( "S", $_encontrados ) ) :
			echo "\nROWS";
			$funcionario->nombre	= $funcionario->nombres . ' ' . $funcionario->apellidos;
			if( oci_execute( $subalternos ) ) :
			
				$funcionario->subalternos		= array();
				while ( $row = oci_fetch_assoc( $subalternos ) ) :
					$row['NOMBRE']	= $row['NOMBRES'] . ' ' . $row['APELLIDOS'];
					if( fopen( _DO_FOTOS_BASE.$row['RUT_FUNCIONARIO'].'.jpg', "r" ) ) :
						$row['FOTO']	= _DO_FOTOS_BASE.$row['RUT_FUNCIONARIO'].'.jpg';
					elseif( fopen( _DO_FOTOS_BASE.$row['RUT_FUNCIONARIO'].'.JPG', "r" ) ) :
						$row['FOTO']	= _DO_FOTOS_BASE.$row['RUT_FUNCIONARIO'].'.JPG';
					endif;
					$row['EMAIL']		= strtolower( $row['EMAIL'] );
					$funcionario->subalternos[]	= $row;
				endwhile;
				oci_free_statement( $subalternos );
			endif;
		else :
			$this->_error	= $_mensaje;
			return false;
		endif;
		
		oci_free_statement( $stmt );
		
		$funcionario->email		= strtolower( $funcionario->email );
		
		/*if( fopen( _DO_FOTOS_BASE.$funcionario->rut.'.jpg', "r" ) ) :
			$funcionario->foto	= _DO_FOTOS_BASE.$funcionario->rut.'.jpg';
		elseif( fopen( _DO_FOTOS_BASE.$funcionario->rut.'.JPG', "r" ) ) :*/
			$funcionario->foto	= _DO_FOTOS_BASE.$funcionario->rut.'.JPG';
		//endif;
		
		/*
		if( count( $rows ) ) :
			foreach( $rows as $i => $row ) :
				foreach( $row as $key => $value ) :
					$rows[$i][$key]	= utf8_decode( $value );
				endforeach;
			endforeach;
		endif;
		*/
	
		/**
		 *
		 
			Array (
				   [RUT_FUNCIONARIO] => 12583945-2
				   [NOMBRES] => MACARENA
				   [APELLIDOS] => BOWEN GARFIAS
				   [EMAIL] =>
				   [FECHA_NACIMIENTO] => 08-FEB-74
				   [RUT_JEFATURA] => 8634949
				   [CARGO] => JEFE DE CAPAC. Y DESARROLLO
				   [UNIDAD] => GERENCIA RECURSOS HUMANOS
				   [JEFATURA] => S
				   [ANEXO] =>
				   )
		 *
		 */
	
		return $funcionario;
	}

	function personas( $filtro=null )
	{
		if( !$this->conexion() ) :
			return false;
		endif;
		
		if( !$filtro || !is_object( $filtro ) ) :
			$this->_error	= $this->errores(1);
			return false;
		endif;
		
		$filtromin		= false;
		
		if( $filtro->nombres && strlen( $filtro->nombres ) > 2 ) :
			trim( $filtro->nombres );
			if( strpos( $filtro->nombres, " " ) ) :
				$arrnombres			= explode( " ", $filtro->nombres );
				$filtro->nombres	= $arrnombres[0] . ' ' . $arrnombres[1];
			endif;
			$filtromin	= strlen( $filtro->nombres ) > 2 ? true : $filtromin;
		else :
			$filtro->nombres	= null;
		endif;
		
		if( $filtro->apaterno && strlen( $filtro->apaterno ) > 2 ) :
			trim( $filtro->apaterno );
			/*if( strpos( $filtro->apaterno, " " ) ) :
				$arrapellidos		= explode( " ", $filtro->apaterno );
				$filtro->apaterno	= $arrapellidos[0] . ' ' . $arrapellidos[1];
			endif;*/
			$filtromin		= strlen( $filtro->apaterno ) > 2 ? true : $filtromin;
		else :
			$filtro->apaterno	= null;
		endif;
		
		if( $filtro->amaterno && strlen( $filtro->amaterno ) > 2 ) :
			trim( $filtro->amaterno );
			$filtromin		= strlen( $filtro->amaterno ) > 2 ? true : $filtromin;
		else :
			$filtro->amaterno	= null;
		endif;
		
		if( $filtro->email && strlen( $filtro->email ) > 2 ) :
			trim( $filtro->email );
			if( strpos( $filtro->email, " " ) ) :
				$arremail			= explode( " ", $filtro->email );
				$filtro->email		= $arremail[0] . ' ' . $arremail[1];
			endif;
			$filtromin	= strlen( $filtro->email ) > 2 ? true : $filtromin;
		else :
			$filtro->email	= null;
		endif;
		
		if( !$filtro->cumplemes || $filtro->cumplemes < 1 ||  $filtro->cumplemes > 12 ) :
			$filtro->cumplemes	= null;
		else :
			$filtromin	= true;
		endif;
		
		if( $filtro->cargo && strlen( $filtro->cargo ) > 2 ) :
			trim( $filtro->cargo );
			if( strpos( $filtro->cargo, " " ) ) :
				$arrcargo			= explode( " ", $filtro->cargo );
				$filtro->cargo		= $arrcargo[0] . ' ' . $arrcargo[1];
			endif;
			$filtromin	= strlen( $filtro->cargo ) > 2 ? true : $filtromin;
		else :
			$filtro->cargo		= null;
		endif;
		
		if( $filtro->unidad && strlen( $filtro->unidad ) > 2 ) :
			trim( $filtro->unidad );
			if( strpos( $filtro->unidad, " " ) ) :
				$arrunidad			= explode( " ", $filtro->unidad );
				$filtro->unidad		= $arrunidad[0] . ' ' . $arrunidad[1];
			endif;
			$filtromin	= strlen( $filtro->unidad ) > 2 ? true : $filtromin;
		else :
			$filtro->unidad		= null;
		endif;
		
		if( !$filtromin ) :
			$this->_error	= $this->errores(2);
			return false;
		endif;
		
		$storeprocedure		= "BEGIN"
							." MEDISYN_1.CAC_INTRANET_PKG.PROC_CONS_FUNCIONARIOS_MASIVA"
							." ( :NOMBRES, :APELLIDO_PATERNO, :APELLIDO_MATERNO, :EMAIL, :MES_CUMPLEANOS, :CARGO, :UNIDAD, :C_ENCONTRADOS, :V_MENSAJE, :C_FUNCIONARIOS );"
							." COMMIT; END;"
							;
		$stmt				= oci_parse( $this->_link, $storeprocedure );
		
		oci_bind_by_name( $stmt, ':NOMBRES', 			$filtro->nombres );
		oci_bind_by_name( $stmt, ':APELLIDO_PATERNO',	$filtro->apaterno );
		oci_bind_by_name( $stmt, ':APELLIDO_MATERNO',	$filtro->amaterno );
		oci_bind_by_name( $stmt, ':EMAIL', 				$filtro->email );
		oci_bind_by_name( $stmt, ':MES_CUMPLEANOS',		$filtro->cumplemes );
		oci_bind_by_name( $stmt, ':CARGO',				$filtro->cargo );
		oci_bind_by_name( $stmt, ':UNIDAD',				$filtro->unidad );
		
		oci_bind_by_name( $stmt,':C_ENCONTRADOS',		$_encontrados );
		oci_bind_by_name( $stmt,':V_MENSAJE',			$_mensaje );
		
		$funcionarios		= oci_new_cursor( $this->_link );
		oci_bind_by_name( $stmt, ':C_FUNCIONARIOS', 	$funcionarios, -1, OCI_B_CURSOR );
		
		if( !@oci_execute( $stmt ) ) :
			$this->_error	= $this->errores(3);
			return false;
		endif;
		
		if( $_encontrados == '' || $_encontrados == 'S' ) :
			if( !oci_execute( $funcionarios ) ) :
				$this->_error	= $this->errores(3);
				return false;
			endif;
			$personas		= array();
			while ( $row = oci_fetch_assoc( $funcionarios ) ) :
			
				/*if( @file_exis( _DO_FOTOS_BASE.$row['RUT_FUNCIONARIO'].'.jpg', "r" ) ) :
					$row['FOTO']	= _DO_FOTOS_BASE.$row['RUT_FUNCIONARIO'].'.jpg';
				elseif( @fopen( _DO_FOTOS_BASE.$row['RUT_FUNCIONARIO'].'.JPG', "r" ) ) :
					$row['FOTO']	= _DO_FOTOS_BASE.$row['RUT_FUNCIONARIO'].'.JPG';
				endif;*/
				$row['FOTO']	= _DO_FOTOS_BASE.$row['RUT_FUNCIONARIO'].'.JPG';
				
				list( $dia, $mes, $ano )	= explode( "-", $row['FECHA_NACIMIENTO'] );
				$ano	= 1900+(int)$ano;
				switch( strtoupper($mes) ) :
					case 'JAN'	:	$mes	= 1;	break;
					case 'FEB'	:	$mes	= 2;	break;
					case 'MAR'	:	$mes	= 3;	break;
					case 'APR'	:	$mes	= 4;	break;
					case 'MAY'	:	$mes	= 5;	break;
					case 'JUN'	:	$mes	= 6;	break;
					case 'JUL'	:	$mes	= 7;	break;
					case 'AUG'	:	$mes	= 8;	break;
					case 'SEP'	:	$mes	= 9;	break;
					case 'OCT'	:	$mes	= 10;	break;
					case 'NOV'	:	$mes	= 11;	break;
					case 'DIC'	:	$mes	= 12;	break;
				endswitch;
				$FN_TIEMPO				= $ano.'-'.$mes.'-'.$dia;
				$row['FN_TIEMPO']		= strtotime( $FN_TIEMPO );
				
				$row['EMAIL']			= strtolower( $row['EMAIL'] );
				
				$personas[]	= $row;
			endwhile;
			oci_free_statement( $funcionarios );
		else :
			$this->_error	= $_mensaje;
			return false;
		endif;
		
		oci_free_statement( $stmt );
		
		/*
		if( count( $personas ) ) :
			foreach( $personas as $i => $row ) :
				foreach( $row as $key => $value ) :
					$personas[$i][$key]	= utf8_decode( $value );
				endforeach;
			endforeach;
		endif;
		*/
		
	
		/**
		 *
		 
			Array (
				   [NOMBRES] => EUGENIA
				   [RUT_FUNCIONARIO] => 13238512
				   [APELLIDOS] => ROSA PE?A
				   [EMAIL] =>
				   [FECHA_NACIMIENTO] => 18-AUG-77
				   [RUT_JEFATURA] => 7492262
				   [CARGO] => ENFERMERA(O) CLINICO
				   [UNIDAD] => PABELLON CENTRAL (1)
				   [ANEXO] => 5487
				   )	 
		 *
		 */
	

		return $personas;
	}
	
	function checkCadena( $params, $cadena="" )
	{
		
	}
		   
	function errores( $nro=0 )
	{
		$errores	= array(
							0	=>	'La conexión de la base de datos MEDISYN no existe',
							1	=>	'La consulta necesita parámetros para ser ejecutada',
							2	=>	'Los parámetros de la consulta, deben tener al menos 3 caracteres y al menos uno de ellos debe estar presente',
							3	=>	'La consulta no se pudo ejecutar',
							4	=>	'El Rut es obligatorio en la consulta de funcionario'
							);
		return $nro >= count( $errores ) ? 'Error Desconocido' : $errores[ $nro ];
	}
}
?>
