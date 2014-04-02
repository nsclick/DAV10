#!/usr/bin/php
<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

class sync_happybirddays{
		
	public static function connect(){
		
		$conn = @oci_connect("INTRANET",  "INTRA", "(DESCRIPTION=
			(FAILOVER=on)
			(LOAD_BALANCE=yes)
			(ADDRESS_LIST=
				(ADDRESS=(PROTOCOL=TCP)(HOST=172.31.2.237)(PORT=1521))
				(ADDRESS=(PROTOCOL=TCP)(HOST=172.31.2.237)(PORT=1521))
			)
			(CONNECT_DATA=
				(FAILOVER_MODE=(TYPE=select)(METHOD=basic))
				(SERVICE_NAME=dav_web)
			)
		)", "AL32UTF8");
		
		return $conn;
	}
	
	public static function closeConn($conn){
		oci_close ( $conn );
	}
	public static function getPeople($conn){
		$storeprocedure		= "BEGIN"
							." MEDISYN_1.CAC_INTRANET_PKG.PROC_CONS_FUNCIONARIOS_MASIVA"
							." ( :NOMBRES, :APELLIDO_PATERNO, :APELLIDO_MATERNO, :EMAIL, :MES_CUMPLEANOS, :CARGO, :UNIDAD, :C_ENCONTRADOS, :V_MENSAJE, :C_FUNCIONARIOS );"
							." COMMIT; END;"
							;
		$stmt				= oci_parse( $conn, $storeprocedure );
		
		$mes = date('m');
		$n = $a1 = $a2 = $e = $c = $u = null;
		oci_bind_by_name( $stmt, ':NOMBRES', 			$n );
		oci_bind_by_name( $stmt, ':APELLIDO_PATERNO',	$a1 );
		oci_bind_by_name( $stmt, ':APELLIDO_MATERNO',	$a2 );
		oci_bind_by_name( $stmt, ':EMAIL', 				$e );
		oci_bind_by_name( $stmt, ':CARGO',				$c );
		oci_bind_by_name( $stmt, ':UNIDAD',				$u );
		
		oci_bind_by_name( $stmt, ':MES_CUMPLEANOS', $mes);
		oci_bind_by_name( $stmt, ':C_ENCONTRADOS',		$_encontrados );
		oci_bind_by_name( $stmt, ':V_MENSAJE',			$_mensaje );
		
		$funcionarios		= oci_new_cursor( $conn );
		oci_bind_by_name( $stmt, ':C_FUNCIONARIOS', 	$funcionarios, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ){
			return false;
		}
		
		if( $_encontrados == '' || $_encontrados == 'S' ){
			if( !oci_execute( $funcionarios ) ) {
				return false;
			}
			
			$personas		= array();
			while ( $row = oci_fetch_assoc( $funcionarios ) ){
				$personas[]	= $row;
			}
			oci_free_statement( $funcionarios );
		} else {
			return false;
		}
		
		oci_free_statement( $stmt );
		return $personas;
	}
	
	public static function writeFile($data=array()){
		
		$json = json_encode($data);
		
		$fl = fopen('modules/mod_cumple/birthdays.json', 'w');
		fwrite($fl, $json);
		fclose($fl);
	}
}

$conn = sync_happybirddays :: connect();
$data = sync_happybirddays :: getPeople($conn);
sync_happybirddays :: closeConn($conn);

sync_happybirddays :: writeFile($data);

