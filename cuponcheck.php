<?php
	require('configuration.php');
	$config	= new JConfig();
	
	$conn	= mysql_connect( $config->host, $config->user, $config->password );
	mysql_select_db( $config->db, $conn );
	
	function revisar($usuario='')
	{
		global $conn;
		$limite		= date('Y-m-d H:i:s' , time() - ( 60*60*24*30*10 ));
		$query 		= "SELECT *"
					. " FROM jos_do_cupones"
					. " WHERE usuario = '" . $usuario . "'"
					. " AND impresion >= '$limite'"
					;
		$res = mysql_query($query, $conn);
		return !mysql_num_rows($res);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
<form name="form1" action="cuponcheck.php" method="post">
<div><label for="usuario">Usuario :</label> <input type="text" name="usuario" id="usuario" value="<?php echo $_REQUEST['usuario'];?>" /></div>
<div><label for="fecha">Fecha Nacimiento :</label> <input type="text" name="fecha" id="fecha" value="<?php echo $_REQUEST['fecha'];?>" /></div>
<div><input type="submit" name="submit" value="Consultar" /></div>
</form>
<hr />
<?php
if( !isset($_REQUEST['usuario']) || !isset($_REQUEST['fecha']) ) :
	echo "<pre>Ingrese los datos para consultar</pre>";
else :
	$fechanacimiento		= $_REQUEST['fecha'];
	$tt						= strtotime($fechanacimiento);
	$cumple					= date("Y-m-d", $tt);
	$ahora					= time();
	list($yy,$mm,$dd)		= explode("-",$cumple);
	
	echo "cumple = $cumple<br />";
	
	$dosMesesAtras			= $ahora - ( 60*60*24*60 );
	echo "dosMesesAtras = ".date('Y-m-d', $dosMesesAtras)."<br />";
	$cumpletimeActual		= strtotime(date('Y').'-'.$mm.'-'.$dd.' 00:00:00');
	echo "cumpletimeActual = ".date('Y-m-d', $cumpletimeActual)."<br />";
	$cumpletimePasado		= strtotime((date('Y')-1).'-'.$mm.'-'.$dd.' 00:00:00');
	echo "cumpletimePasado = ".date('Y-m-d', $cumpletimePasado)."<br />";	
	
	$tieneCumple				= false;
	if( ( $cumpletimePasado >= $dosMesesAtras  || ( $cumpletimeActual >= $dosMesesAtras && $cumpletimeActual <= $ahora ) ) && revisar($_REQUEST['usuario']) ) :
		$tieneCumple			= true;
	endif;
	
	echo $tieneCumple ? "<pre>Tiene Cupon</pre>" : "<pre>NO Tiene Cupon</pre>";
endif;
?>
</body>
</html>