<?php 

function IPprivada() {
	$octetos	= explode( ".", $_SERVER['REMOTE_ADDR'] );
	switch( $octetos[0] ) {
		// IP's privadas
		case '10'	:
			return true;
			break;
		case '172'	:
			return (int)$octetos[1] >= 16 && (int)$octetos[1] <= 31;
			break;
		case '192'	:
			return $octetos[1] == '168';
			break;
		// VLan
		case '9'	:
			return $octetos[1] == '5';
			break;
		default		:
			return false;
	}
}

?>