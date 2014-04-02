<html>
<head>
<title>Prueba Masiva</title>
</head>
<body>
<form name="form1" method="post">
	Nombres: <input type="text" name="nombres" value="<?php echo $_POST['nombres'];?>" /><br>
    Ap. Paterno: <input type="text" name="apaterno" value="<?php echo $_POST['apaterno'];?>" /><br>
    Ap. Materno: <input type="text" name="amaterno" value="<?php echo $_POST['amaterno'];?>" /><br>
    Email: <input type="text" name="email" value="<?php echo $_POST['email'];?>" /><br>
    Cumple Mes: <input type="text" name="cumplemes" value="<?php echo $_POST['cumplemes'];?>" /><br>
    Cargo: <input type="text" name="cargo" value="<?php echo $_POST['cargo'];?>" /><br>
    Unidad: <input type="text" name="unidad" value="<?php echo $_POST['unidad'];?>" /><br>
    <input type="submit" name="sbm" value="Buscar" />
    <hr />
<?php

ini_set("display_errors","On");
error_reporting(E_ALL);

if( (isset($_POST['nombres']) && $_POST['nombres'] != "")
	|| (isset($_POST['apaterno']) && $_POST['apaterno'] != "")
	|| (isset($_POST['amaterno']) && $_POST['amaterno'] != "")
	|| (isset($_POST['email']) && $_POST['email'] != "")
	|| (isset($_POST['cumplemes']) && $_POST['cumplemes'] != "")
	|| (isset($_POST['cargo']) && $_POST['cargo'] != "")
	|| (isset($_POST['unidad']) && $_POST['unidad'] != "")
															) :
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
	if( $dblink ) :
		echo "oci_connect OK<br /><br />";
	else :
		echo "oci_connect Error<br /><br />";
	endif;
	
	echo "<pre>";

	$sql	= "BEGIN MEDISYN_1.CAC_INTRANET_PKG.PROC_CONS_FUNCIONARIOS_MASIVA ( :NOMBRES, :APELLIDO_PATERNO, :APELLIDO_MATERNO, :EMAIL, :MES_CUMPLEANOS, :CARGO, :UNIDAD, :C_ENCONTRADOS, :V_MENSAJE, :C_FUNCIONARIOS ); COMMIT; END;";
	
	$stmt = oci_parse($dblink,$sql);
	
	//$rut	= (int)$_POST['rut'];
	//oci_bind_by_name($stmt,':N_RUT_FUNCIONARIO',$rut, 32);
	// Bind the output parameter
	$nombres			= $_POST['nombres'];
	oci_bind_by_name($stmt,':NOMBRES',$nombres,20);
	// Bind the output parameter
	$apellidoPaterno	= $_POST['apaterno'];
	oci_bind_by_name($stmt,':APELLIDO_PATERNO',$apellidoPaterno,20);
	// Bind the output parameter
	$apellidoMaterno	= $_POST['amaterno'];
	oci_bind_by_name($stmt,':APELLIDO_MATERNO',$apellidoMaterno,20);
	// Bind the output parameter
	$email				= $_POST['email'];
	oci_bind_by_name($stmt,':EMAIL',$email,40);
	// Bind the output parameter
	$cumplemes			= $_POST['cumplemes'];
	oci_bind_by_name($stmt,':MES_CUMPLEANOS',$cumplemes,32);
	// Bind the output parameter
	$cargo				= $_POST['cargo'];
	oci_bind_by_name($stmt,':CARGO',$cargo,80);
	// Bind the output parameter
	$unidad				= $_POST['unidad'];
	oci_bind_by_name($stmt,':UNIDAD',$unidad,40);
	// Bind the output parameter
	//$superior	= (int)0;
	//oci_bind_by_name($stmt,':SUPERIOR',$superior,32);
	// Bind the output parameter
	//$anexo	= (int)0;
	//oci_bind_by_name($stmt,':ANEXO',$anexo,32);
	// Bind the output parameter
	oci_bind_by_name($stmt,':C_ENCONTRADOS',$c_encontrado,200);
	// Bind the output parameter
	oci_bind_by_name($stmt,':V_MENSAJE',$v_mensaje,200);
	// Create a new cursor resource
	$rows = oci_new_cursor($dblink);
	// Bind the cursor resource to the Oracle argument
	oci_bind_by_name( $stmt, ':C_FUNCIONARIOS', $rows, -1, OCI_B_CURSOR );
	//oci_bind_by_name( $stmt, ':C_SUBALTERNOS', 		$subalternos, -1, OCI_B_CURSOR );
	//oci_bind_by_name($stmt,":C_SUBALTERNOS",$subordinados);

	oci_execute($stmt);
/*
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
*/

	//if( $c_encontrado == 'S' ) :
		// Execute the cursor
		oci_execute($rows);
		
		// Use OCIFetchinto in the same way as you would with SELECT
		while ($entry = oci_fetch_assoc($rows)) {
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