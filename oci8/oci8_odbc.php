<?php
	//$dblink = oci_connect("INTRANET",  "INTRA");
	//$dblink = odbc_connect("ORACLE", "INTRANET", "INTRA", SQL_CUR_USE_ODBC);
	$dblink = odbc_connect("ORACLE", "INTRANET", "INTRA");
	if( $dblink ) :
		echo "OK<br /><br />";
	else :
		echo "Error<br /><br />";
	endif;

	$val = array();

	//$sql = "CAC_INTRANET_PKG.PROC_CONSULTA_FUNCIONARIO('','','','','','','','','','');";
	//$sql = "CAC_INTRANET_PKG.PROC_CONSULTA_FUNCIONARIO('','','','','','','','','','')";
	//$sql = "CAC_INTRANET_PKG.PROC_CONSULTA_FUNCIONARIO();";
	//$sql = "BEGIN CAC_INTRANET_PKG.PROC_CONSULTA_FUNCIONARIO('','','','','','','','','',''); END;";
	//$sql = "cac_intranet_pkg.proc_consulta_funcionario()";
	//$sql = "BEGIN CAC_INTRANET_PKG.PROC_CONSULTA_FUNCIONARIO(N_RUT_FUNCIONARIO='',:'',:'',:'',:'',:'',:'',:'',:'',:''); END;";
	//$sql = "BEGIN CAC_INTRANET_PKG.PROC_CONSULTA_FUNCIONARIO(N_RUT_FUNCIONARIO='',NOMBRES='',APELLIDOS='',EMAIL='',FECHA_NACIMIENTO='',CARGO='',UNIDAD='',SUPERIOR='',C_ENCONTRADO='',V_MENSAJE=''); END;";

	$c_encontrado	= null;
	$v_mensaje		= null;
	$rut			= 16149204;

	$sql = ""
	. "DECLARE \n"
	. "N_RUT_FUNCIONARIO NUMBER; \n"
	. "NOMBRES VARCHAR2(20); \n"
	. "APELLIDOS VARCHAR2(20); \n"
	. "EMAIL VARCHAR2(40); \n"
	. "FECHA_NACIMIENTO DATE; \n"
	. "CARGO VARCHAR2(80); \n"
	. "UNIDAD VARCHAR2(20); \n"
	. "SUPERIOR NUMBER; \n"
	. "C_ENCONTRADO VARCHAR2(32767); \n"
	. "V_MENSAJE VARCHAR2(32767); \n"
	. "C_SUBALTERNOS MEDISYN_1.CAC_INTRANET_PKG.C_CURSOR; \n"
	. "BEGIN \n"
	. "N_RUT_FUNCIONARIO := NULL; \n"
	//. "N_RUT_FUNCIONARIO := 16149204; \n"
	//. "N_RUT_FUNCIONARIO := 7012551; \n"
	. "NOMBRES := NULL; \n"
	. "APELLIDOS := NULL; \n"
	. "EMAIL := NULL; \n"
	. "FECHA_NACIMIENTO := NULL; \n"
	. "CARGO := NULL; \n"
	. "UNIDAD := NULL; \n"
	. "SUPERIOR := NULL; \n"
	. "C_ENCONTRADO := NULL; \n"
	. "V_MENSAJE := NULL; \n"
	//. "MEDISYN_1.CAC_INTRANET_PKG.PROC_CONSULTA_FUNCIONARIO(N_RUT_FUNCIONARIO,\n"
	. "MEDISYN_1.CAC_INTRANET_PKG.PROC_CONSULTA_FUNCIONARIO(?,\n"
	//. "NOMBRES, APELLIDOS, EMAIL, FECHA_NACIMIENTO, CARGO, UNIDAD, SUPERIOR, C_ENCONTRADO, V_MENSAJE, C_SUBALTERNOS); COMMIT; END;"
	. "NOMBRES, APELLIDOS, EMAIL, FECHA_NACIMIENTO, CARGO, UNIDAD, SUPERIOR, C_ENCONTRADO, V_MENSAJE, C_SUBALTERNOS); COMMIT; END;"
	//. "NOMBRES, APELLIDOS, EMAIL, FECHA_NACIMIENTO, CARGO, UNIDAD, SUPERIOR, ?, V_MENSAJE, C_SUBALTERNOS); COMMIT; END;"
	//. "NOMBRES, APELLIDOS, EMAIL, FECHA_NACIMIENTO, CARGO, UNIDAD, SUPERIOR, ?, V_MENSAJE, C_SUBALTERNOS); COMMIT; END;"
	;

	$stmt	= odbc_prepare($dblink, $sql );
	
	//if( !$rs = odbc_exec($dblink, $sql ) )
	if( !$rs = odbc_execute($stmt, array($c_encontrado) ) )
	{
		print_r( odbc_error( $dblink) );
	}else
	{
		echo "CONSULTA OK<br />";
		/*echo "Nro. rows = " . odbc_num_rows( $rs ) . ";<br />";
		while( $rows = odbc_fetch_row($rs) ){
			array_push($val, $row);
		}
		odbc_free_result( $rs );*/
		print_r( $rs );
		//$row = odbc_fetch_object($rs);
		print_r( odbc_result_all($rs) );
		//print_r( $v_mensaje );
	}
	odbc_close( $dblink );
	
?>