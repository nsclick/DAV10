<?php

function mostrarFunc($func) {
    printf(
        "\n\n===> La función %s '%s'\n".
        "     declarada en %s\n".
        "     líneas %d a %d\n",
        $func->isInternal() ? 'interna' : 'definida por el usuario',
        $func->getName(),
        $func->getFileName(),
        $func->getStartLine(),
        $func->getEndline()
    );
    printf("---> Documentación:\n %s\n", var_export($func->getDocComment(), 1));
    if ($statics = $func->getStaticVariables()) {
        printf("---> Variables estáticas: %s\n", var_export($statics, 1));
    }
}

function fechaDesde($fecha) {

	$fecha = strtotime($fecha);

    $fecha = time() - $fecha;

    $divisiones = array (
        31536000 => 'año',
        2592000 => 'mes',
        604800 => 'semana',
        86400 => 'día',
        3600 => 'hora',
        60 => 'minuto',
        1 => 'segundo'
    );

    foreach ($divisiones as $unidad => $texto) {
        if ($fecha < $unidad) continue;
        $numeroUnidad = floor($fecha / $unidad);
        if($texto == "mes") {
        	return $numeroUnidad . ' ' . $texto . (($numeroUnidad > 1) ? 'es' : '');
        } else {
        	return $numeroUnidad . ' ' . $texto . (($numeroUnidad > 1) ? 's' : '');
        }
    }

}

function limpiarString($str) {
	
	$busca = array(
    	'@<script [^>]*?>.*?@si',           // quita el javascript
    	'@< [/!]*?[^<>]*?>@si',            	// quita las etiquetas html
    	'@<style [^>]*?>.*?</style>@siU',   // quita el css
    	'@< ![sS]*?--[ tnr]*>@'         	// quita multi líneas
  	);
    
    $salida = preg_replace($busca, '', $str);
    
    return $salida;
}

function sanitizar($str) {
    
    if (is_array($str)) {
        foreach($str as $var=>$val) {
            $salida[$var] = sanitizar($val);
        }
    } else {
        if (get_magic_quotes_gpc()) {
            $str = stripslashes($str);
        }
        $str  = limpiarString($str);
        $salida = mysql_real_escape_string($str);
    }
    return $salida;
}

function _db($arr, $e = false) {

	echo '<pre>';
	print_r($arr);
	echo '</pre>';

	if($e) 
		exit;
}

function javascript_encode($arr){

	$i = 0;
	$cant = count($arr)-1;
	$js = "[";

	foreach($arr as $key => $val){

		if($i == $cant){
			$js .= "{ value: " . $val['value'] . ", text: '" . $val['text'] . "' }";
		} else {
			$js .= "{ value: " . $val['value'] . ", text: '" . $val['text'] . "' },";
		}

		$i++;
	}

	$js .= "]";

	return $js;
}