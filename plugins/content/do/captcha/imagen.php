<?php
$texto		= base64_decode($_GET["t"]);
$captcha	= imagecreatefromgif("bgcaptcha.gif");
$colText	= imagecolorallocate($captcha, 255, 255, 255);
imagestring($captcha, 5, 4, 1, $texto, $colText);
header("Content-type: image/gif");
imagegif($captcha);

?>
