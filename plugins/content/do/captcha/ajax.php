<?php
function textoAleatorio($length) {
    $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
    for($i=0;$i<$length;$i++) {
      $key .= $pattern{mt_rand(0,35)};
    }
    return $key;
}

$juribase				= $_POST['juribase'];
$textoAleatorio			= textoAleatorio(8);
$textoAleatorio_md5		= md5($textoAleatorio);
$textoAleatorio_base64	= base64_encode($textoAleatorio);
	
echo 'Ingrese el c&oacute;digo de esta imagen <img src="'.$juribase.'plugins/content/dicoex/captcha/imagen.php?t='.$textoAleatorio_base64.'" widht="80" height="20" alt="" border="0" /> aqu&iacute;: <input type="text" name="cc_codigo" class="inputbox" size="10" maxlength="8" /><input type="hidden" name="cc_codigo_secret" value="'.$textoAleatorio_md5.'" />';
?>
