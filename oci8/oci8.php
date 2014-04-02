<?php
	//$dblink = oci_connect("INTRANET",  "INTRA", "DAVILA_INTRA", "AL32UTF8");
	$dblink = oci_connect("INTRANET",  "INTRA", '(DESCRIPTION =
    (ADDRESS =
      (PROTOCOL = TCP)
      (HOST = mercurio-vip.davila.cl)
      (PORT = 1521)
    )
    (ADDRESS =
      (PROTOCOL = TCP)
      (HOST = afrodita-vip.davila.cl)
      (PORT = 1521)
    )
    (ADDRESS =
      (PROTOCOL = TCP)
      (HOST = pegaso-vip.davila.cl)
      (PORT = 1521)
    )
    (ADDRESS =
      (PROTOCOL = TCP)
      (HOST = venus-vip.davila.cl)
      (PORT = 1521)
    )
    (LOAD_BALANCE = yes)
    (CONNECT_DATA =
      (SERVER = DEDICATED)
      (SERVICE_NAME = dav_RCE)
      (FAILOVER_MODE =
        (TYPE = SELECT)
        (METHOD = BASIC)
        (RETRIES = 180)
        (DELAY = 5)
      )
    )
  )');	//$dblink = oci_connect("INTRANET",  "INTRA", '//mercurio-vip.davila.cl:1521/dav_RCE');
	
	//$dblink = odbc_connect("ORACLE", "INTRANET", "INTRA", SQL_CUR_USE_ODBC);
	//$dblink = odbc_connect("ORACLE", "INTRANET", "INTRA");
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

	/*
	$c_encontrado	= null;
	$v_mensaje		= null;
	$rut			= 16149204;
	*/

	/*
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
	. "MEDISYN_1.CAC_INTRANET_PKG.PROC_CONSULTA_FUNCIONARIO(N_RUT_FUNCIONARIO,\n"
	//. "MEDISYN_1.CAC_INTRANET_PKG.PROC_CONSULTA_FUNCIONARIO(?,\n"
	//. "NOMBRES, APELLIDOS, EMAIL, FECHA_NACIMIENTO, CARGO, UNIDAD, SUPERIOR, C_ENCONTRADO, V_MENSAJE, C_SUBALTERNOS); COMMIT; END;"
	. "NOMBRES, APELLIDOS, EMAIL, FECHA_NACIMIENTO, CARGO, UNIDAD, SUPERIOR, C_ENCONTRADO, V_MENSAJE, C_SUBALTERNOS); COMMIT; END;"
	//. "NOMBRES, APELLIDOS, EMAIL, FECHA_NACIMIENTO, CARGO, UNIDAD, SUPERIOR, ?, V_MENSAJE, C_SUBALTERNOS); COMMIT; END;"
	//. "NOMBRES, APELLIDOS, EMAIL, FECHA_NACIMIENTO, CARGO, UNIDAD, SUPERIOR, ?, V_MENSAJE, C_SUBALTERNOS); COMMIT; END;"
	;
	*/
	
	$sql2 = ""
	//. "BEGIN MEDISYN_1.CAC_INTRANET_PKG.PROC_CONSULTA_FUNCIONARIO(N_RUT_FUNCIONARIO,\n"
	. "BEGIN MEDISYN_1.CAC_INTRANET_PKG.PROC_CONSULTA_FUNCIONARIO(:N_RUT_FUNCIONARIO,\n"
	//. "MEDISYN_1.CAC_INTRANET_PKG.PROC_CONSULTA_FUNCIONARIO(?,\n"
	//. "NOMBRES, APELLIDOS, EMAIL, FECHA_NACIMIENTO, CARGO, UNIDAD, SUPERIOR, C_ENCONTRADO, V_MENSAJE, C_SUBALTERNOS); COMMIT; END;"
	//. "NOMBRES, APELLIDOS, EMAIL, FECHA_NACIMIENTO, CARGO, UNIDAD, SUPERIOR, C_ENCONTRADO, V_MENSAJE, C_SUBALTERNOS); COMMIT; END;"
	. ":NOMBRES, :APELLIDOS, :EMAIL, :FECHA_NACIMIENTO, :CARGO, :UNIDAD, :SUPERIOR, :C_ENCONTRADO, :V_MENSAJE, :C_SUBALTERNOS); COMMIT; END;"
	//. "NOMBRES, APELLIDOS, EMAIL, FECHA_NACIMIENTO, CARGO, UNIDAD, SUPERIOR, ?, V_MENSAJE, C_SUBALTERNOS); COMMIT; END;"
	//. "NOMBRES, APELLIDOS, EMAIL, FECHA_NACIMIENTO, CARGO, UNIDAD, SUPERIOR, ?, V_MENSAJE, C_SUBALTERNOS); COMMIT; END;"
	;
	
	$sql	= "BEGIN MEDISYN_1.CAC_INTRANET_PKG.PROC_CONSULTA_FUNCIONARIO ( :N_RUT_FUNCIONARIO, :NOMBRES, :APELLIDOS, :EMAIL, :FECHA_NACIMIENTO, :CARGO, :UNIDAD, :SUPERIOR, :C_ENCONTRADO, :V_MENSAJE, :C_SUBALTERNOS ); COMMIT; END;";

	
	$stmt = oci_parse($dblink,$sql);
	
	// Assign a value to the input 
	//  Bind the input parameter
	//oci_bind_by_name($stmt,':N_RUT_FUNCIONARIO',$rut);
	//$rut = 16149204;
	//$rut = null;
		//$rut			= 86349493;
		//$rut			= 70125510;
		//$rut			= 7012551;
		//$rut			= 16149204;
		$rut			= 113674070;
		//$rut			= 8329235;
		//$rut			= 7857895;
		//$superior		= 86349493;
	oci_bind_by_name($stmt,':N_RUT_FUNCIONARIO',$rut);
	//$rut = 16149204;
	//$rut = 8634949;
	// Bind the output parameter
	oci_bind_by_name($stmt,':NOMBRES',$nombres);
	// Bind the output parameter
	oci_bind_by_name($stmt,':APELLIDOS',$apellidos);
	// Bind the output parameter
	oci_bind_by_name($stmt,':EMAIL',$email);
	// Bind the output parameter
	oci_bind_by_name($stmt,':FECHA_NACIMIENTO',$fnac);
	// Bind the output parameter
	oci_bind_by_name($stmt,':CARGO',$cargo);
	// Bind the output parameter
	oci_bind_by_name($stmt,':UNIDAD',$unidad);
	// Bind the output parameter
	oci_bind_by_name($stmt,':SUPERIOR',$superior);
	// Bind the output parameter
	oci_bind_by_name($stmt,':C_ENCONTRADO',$c_encontrado,200);
	// Bind the output parameter
	oci_bind_by_name($stmt,':V_MENSAJE',$v_mensaje,200);
	// Create a new cursor resource
	$subordinados = oci_new_cursor($dblink);
	// Bind the cursor resource to the Oracle argument
	oci_bind_by_name( $stmt, ':C_SUBALTERNOS', $subordinados, -1, OCI_B_CURSOR );
	//oci_bind_by_name( $stmt, ':C_SUBALTERNOS', 		$subalternos, -1, OCI_B_CURSOR );
	//oci_bind_by_name($stmt,":C_SUBALTERNOS",$subordinados);

	
	oci_execute($stmt);

	print_r( $c_encontrado );
	echo "<br />";
	print_r( $v_mensaje );
	
	if( $c_encontrado == 'S' ) :
		// Execute the cursor
		oci_execute($subordinados);
		
		// Use OCIFetchinto in the same way as you would with SELECT
		while ($entry = oci_fetch_assoc($subordinados)) {
			print_r($entry);
		}
	endif;
	
	echo "<br /><br />MASIVA<br /><br />";
	
	$sqltres	="BEGIN MEDISYN_1.CAC_INTRANET_PKG.PROC_CONS_FUNCIONARIOS_MASIVA ( :NOMBRES, :APELLIDOS, :EMAIL, :MES_CUMPLEANOS, :CARGO, :UNIDAD, :C_ENCONTRADOS, :V_MENSAJE, :C_FUNCIONARIOS ); COMMIT; END;";
	$stmtdos = oci_parse($dblink,$sqltres);
	oci_bind_by_name($stmtdos,':NOMBRES',$n);
	//$n = "daniel adolfo";
	oci_bind_by_name($stmtdos,':APELLIDOS',$apelli);
	$apelli = "bowen";
	oci_bind_by_name($stmtdos,':EMAIL',$e);
	oci_bind_by_name($stmtdos,':MES_CUMPLEANOS',$mc);
	//$mc = 8;
	oci_bind_by_name($stmtdos,':CARGO',$c);
	oci_bind_by_name($stmtdos,':UNIDAD',$u);
	oci_bind_by_name($stmtdos,':NOMBRES',$n);
	// Bind the output parameter
	oci_bind_by_name($stmtdos,':C_ENCONTRADOS',$ce,200);
	// Bind the output parameter
	oci_bind_by_name($stmtdos,':V_MENSAJE',$vm,200);
	// Create a new cursor resource
	$funcionarios = oci_new_cursor($dblink);
	// Bind the cursor resource to the Oracle argument
	oci_bind_by_name($stmtdos,":C_FUNCIONARIOS",$funcionarios,-1,OCI_B_CURSOR);
	
	oci_execute($stmtdos);

	print_r( $ce );
	echo "<br />";
	print_r( $vm );
	
	//if( $ce == 'S' ) :
		// Execute the cursor
		oci_execute($funcionarios);
		
		// Use OCIFetchinto in the same way as you would with SELECT
		echo "<pre>";
		while ($entrydos = oci_fetch_assoc($funcionarios)) {
		//while ($entrydos = oci_fetch_row($funcionarios)) {
				list( $dia, $mes, $ano )	= explode( "-", $entrydos['FECHA_NACIMIENTO'] );
				$ano	= 1900+(int)$ano;
				switch( strtoupper($mes) ) :
					/*
					case 'JAN'	:	$entrydos['FN_TIEMPO']	= strtotime( "$ano-01-$dia" );	break;
					case 'FEB'	:	$entrydos['FN_TIEMPO']	= strtotime( "$ano-02-$dia" );	break;
					case 'MAR'	:	$entrydos['FN_TIEMPO']	= strtotime( "$ano-03-$dia" );	break;
					case 'APR'	:	$entrydos['FN_TIEMPO']	= strtotime( "$ano-04-$dia" );	break;
					case 'MAY'	:	$entrydos['FN_TIEMPO']	= strtotime( "$ano-05-$dia" );	break;
					case 'JUN'	:	$entrydos['FN_TIEMPO']	= strtotime( "$ano-06-$dia" );	break;
					case 'JUL'	:	$entrydos['FN_TIEMPO']	= strtotime( "$ano-07-$dia" );	break;
					case 'AUG'	:	$entrydos['FN_TIEMPO']	= strtotime( "$ano-08-$dia" );	break;
					case 'SEP'	:	$entrydos['FN_TIEMPO']	= strtotime( "$ano-09-$dia" );	break;
					case 'OCT'	:	$entrydos['FN_TIEMPO']	= strtotime( "$ano-10-$dia" );	break;
					case 'NOV'	:	$entrydos['FN_TIEMPO']	= strtotime( "$ano-11-$dia" );	break;
					case 'DIC'	:	$entrydos['FN_TIEMPO']	= strtotime( "$ano-12-$dia" );	break;
					*/
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
			$FN_TIEMPO				= strtotime( $FN_TIEMPO );
			$entrydos['FN_TIEMPO']	= date( "Y-m-d", $FN_TIEMPO );
			print_r($entrydos);
		}
		echo "</pre>";
	//endif;
	
	oci_close( $dblink );

/*
	$stmt	= odbc_prepare($dblink, $sql );
	
	//if( !$rs = odbc_exec($dblink, $sql ) )
	if( !$rs = odbc_execute($stmt, array($c_encontrado) ) )
	{
		print_r( odbc_error( $dblink) );
	}else
	{
		echo "CONSULTA OK<br />";
		odbc_free_result( $rs );
		print_r( $rs );
		//$row = odbc_fetch_object($rs);
		print_r( odbc_result_all($rs) );
		//print_r( $v_mensaje );
	}
	odbc_close( $dblink );
	*/
?>