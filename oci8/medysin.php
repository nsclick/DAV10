<html>
<head>
<title>Prueba Funcionario</title>
</head>
<body>
<form name="form1" method="post">
	<input type="text" name="rut" value="<?php echo $_POST['rut'];?>" />
    <input type="submit" name="sbm" value="Buscar" />
    <hr />
<?php

ini_set("display_errors","On");
error_reporting(E_ALL);

if( isset($_POST['rut']) && $_POST['rut'] != "" ) :
	//$dblink = oci_connect("INTRANET",  "INTRA", "DAVILA_INTRA", "AL32UTF8");
	$dblink = oci_connect("INTRANET",  "INTRA", '(DESCRIPTION=
   (FAILOVER=on)
   (LOAD_BALANCE=yes)
   (ADDRESS_LIST=
    (ADDRESS=(PROTOCOL=TCP)(HOST=davila-vip-scan1.tisal.cl)(PORT=1521))
    (ADDRESS=(PROTOCOL=TCP)(HOST=davila-vip-scan2.tisal.cl)(PORT=1521))
   )
   (CONNECT_DATA=
     (FAILOVER_MODE=(TYPE=select)(METHOD=basic))
     (SERVICE_NAME=dav_web)
   )
 )');
	if( $dblink ) :
		echo "oci_connect OK<br /><br />";
	else :
		echo "oci_connect Error<br /><br />";
	endif;
	
	echo "<pre>";

	$sql	= "BEGIN MEDISYN_1.CAC_INTRANET_PKG.PROC_CONSULTA_FUNCIONARIO ( :N_RUT_FUNCIONARIO, :NOMBRES, :APELLIDOS, :EMAIL, :FECHA_NACIMIENTO, :CARGO, :UNIDAD, :SUPERIOR, :ANEXO, :C_ENCONTRADO, :V_MENSAJE, :C_SUBALTERNOS ); COMMIT; END;";
	
	$stmt = oci_parse($dblink,$sql);
	
	$rut	= (int)$_POST['rut'];
	oci_bind_by_name($stmt,':N_RUT_FUNCIONARIO',$rut, 32);
	// Bind the output parameter
	//$nombres	= "";
	oci_bind_by_name($stmt,':NOMBRES',$nombres,20);
	// Bind the output parameter
	//$apellidos	= "";
	oci_bind_by_name($stmt,':APELLIDOS',$apellidos,20);
	// Bind the output parameter
	//$email		= "";
	oci_bind_by_name($stmt,':EMAIL',$email,40);
	// Bind the output parameter
	//$fnac		= null;
	oci_bind_by_name($stmt,':FECHA_NACIMIENTO',$fnac,32);
	// Bind the output parameter
	//$cargo		= "";
	oci_bind_by_name($stmt,':CARGO',$cargo,80);
	// Bind the output parameter
	//$unidad		= "";
	oci_bind_by_name($stmt,':UNIDAD',$unidad,40);
	// Bind the output parameter
	//$superior	= (int)0;
	oci_bind_by_name($stmt,':SUPERIOR',$superior,32);
	// Bind the output parameter
	//$anexo	= (int)0;
	oci_bind_by_name($stmt,':ANEXO',$anexo,32);
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

	print_r( $rut );
	echo "<br />";
	print_r( $nombres );
	echo "<br />";
	print_r( $apellidos );
	echo "<br />";
	print_r( $email );
	echo "<br />";
	print_r( $fnac );
	echo "<br />";
	print_r( $cargo );
	echo "<br />";
	print_r( $unidad );
	echo "<br />";
	print_r( $superior );
	echo "<br />";
	print_r( $c_encontrado );
	echo "<br />";
	print_r( $v_mensaje );
	
	//if( $c_encontrado == 'S' ) :
		// Execute the cursor
		oci_execute($subordinados);
		
		// Use OCIFetchinto in the same way as you would with SELECT
		while ($entry = oci_fetch_assoc($subordinados)) {
			print_r($entry);
		}
	//endif;
	
	echo "</pre>";

	oci_close( $dblink );
endif;
?>
</form>
</body>
</html>