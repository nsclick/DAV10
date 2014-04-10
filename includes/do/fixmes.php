<?php

function fixMes( $mes = '' ) {
	$fix	= '';
	$mes	= ucfirst( $mes );
	
	switch( $mes ) {
		case 'January'		:
		case 'Enero'		:
			$fix			= 'Enero';
		break;
		case 'February'		:
		case 'Febrero'		:
			$fix			= 'Febrero';
		break;
		case 'March'		:
		case 'Marzo'		:
			$fix			= 'Marzo';
		break;
		case 'April'		:
		case 'Abril'		:
			$fix			= 'Abril';
		break;
		case 'May'			:
		case 'Mayo'			:
			$fix			= 'Mayo';
		break;
		case 'June'			:
		case 'Junio'		:
			$fix			= 'Junio';
		break;
		case 'July'			:
		case 'Julio'		:
			$fix			= 'Julio';
		break;
		case 'August'		:
		case 'Agosto'		:
			$fix			= 'Agosto';
		break;
		case 'September'	:
		case 'Septiembre'	:
			$fix			= 'Septiembre';
		break;
		case 'October'		:
		case 'Octubre'		:
			$fix			= 'Octubre';
		break;
		case 'November'		:
		case 'Noviembre'	:
			$fix			= 'Noviembre';
		break;
		case 'December'		:
		case 'Diciembre'	:
			$fix			= 'Diciembre';
		break;
	}

	return $fix;
}

?>