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

	$rut			= $_POST['rut'];
	if(!$conn		= oci_connect("m4des_davila_interfaz",  "davinterfaz3887", ""
																	. "(DESCRIPTION =\n"
																	. "  (ADDRESS_LIST =\n"
																	. "    (ADDRESS = (PROTOCOL = TCP)(HOST = 164.77.11.45)(PORT = 1521))\n"
																	. "  )\n"
																	. "  (CONNECT_DATA =\n"
																	. "    (SID = sadm)\n"
																	. "  )\n"
																	. ")"
																	, "AL32UTF8") ):
		$e = oci_error();
		echo htmlentities($e['message'], ENT_QUOTES);
	endif;
	
//  	$sql		= "SELECT ID_SOCIEDAD, ID_EMPLEADO, N_EMPLEADO, ID_SUPERIOR, FEC_ULT_ACTUALIZACION FROM m4_autentificacion_usuarios WHERE id_empleado = '$rut'";
	$sql		= "SELECT * FROM m4_autentificacion_usuarios WHERE id_empleado = '$rut'";
	$stid		= oci_parse($conn, $sql);

	oci_execute($stid);

	$numrows	= 0;
	//if( oci_num_rows($stid) ) :
		// Each fetch populates the previously defined variables with the next row's data
		while ($row = oci_fetch_assoc($stid)) {
			echo "<pre>"; print_r($row); echo "</pre>";
		}
	//else :

	oci_free_statement($stid);
	oci_close($conn);
		
endif;
?>
</form>
</body>
</html>