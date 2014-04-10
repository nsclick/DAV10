<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Oci8 :: Medysin Speed</title>
</head>

<body>
<?php
$inicio			= time();
$iniciofecha	= date("d/m/Y H:i:s", $inicio);
echo "Inicio : $iniciofecha<br /><br />";

ini_set("display_errors","On");
error_reporting(E_ALL);

// Try connecting to the database

$connstruno	= '(DESCRIPTION =
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
		)'
		;

$connstrdos	= '(DESCRIPTION = 
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
      (SERVER = SHARED)
      (SERVICE_NAME = dav_rce)
      (FAILOVER_MODE = 
        (TYPE = SELECT)
        (METHOD = BASIC)
        (RETRIES = 180)
        (DELAY = 5)
      )
    )
  )'
;

//echo $connstruno."<br />";

$conn = oci_connect("INTRANET",  "INTRA", $connstrdos);

if (!$conn) {
	$e = oci_error();   // For oci_connect errors pass no handle
	echo '<b><font color="red">CONEXIÓN FALLÓ</font></b> : ' . htmlentities($e['message']);
} else {
    echo '<b><font color="green">CONEXIÓN OK!</font></b>';
    oci_close($conn);
}

$termino		= time();
$terminofecha	= date("d/m/Y H:i:s", $termino);
echo "<br /><br />Término : $terminofecha<br />";

$duracion		= (int)$termino - (int)$inicio;
echo "Duración : $duracion segundos<br />";

?>
</body>
</html>