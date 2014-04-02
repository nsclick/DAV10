<?php

	setlocale(LC_ALL, 'es_ES','es_ES.utf8','spanish');
	
	echo strftime("%B")."<br />";
	echo date('Y-m-d H:i:s')."<br />";
	echo date('Y-m-d H:i:s', time())."<br />";
	
	
?>