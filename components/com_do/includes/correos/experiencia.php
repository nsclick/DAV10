<?php

function _plantilla( &$recipiente, &$cc, &$bcc, &$sujeto, &$acuserecivo, &$html, &$adjuntos, &$msg ){

	$recipiente		= array();
	$recipiente[]	= "sebastian@do.cl";
	//$recipiente[]	= "portalchilesomostodos@gmail.com";
	$cc				= NULL;
	$bcc			= NULL;
	$sujeto			= "Cuéntanos tu Experiencia";
	$acuserecivo	= NULL;
//	$acuserecivo	= strval( mosGetParam( $_REQUEST, 'Email', '' ) );
	$html			= 0;
	$adjuntos		= NULL;
	$msg			= "Gracias por su aporte a nuestro sitio";
	
	if( $_FILES["foto"]["tmp_name"] ) :
		$adjuntos[0]["ruta"]	= $_FILES["foto"]["tmp_name"];
		$adjuntos[0]["nombre"]	= $_FILES["foto"]["name"];
	endif;
	
	// datos formulario
	$nombre			= JRequest::getVar('nombre','','request','string');
	$pais			= JRequest::getVar('pais','','request','string');
	$email			= JRequest::getVar('email','','request','string');
	$comentarios	= JRequest::getVar('comentarios','','request','string');

	// cuerpo
	$contenidoTXT = "\n\nDatos:\n";
	$contenidoTXT .= "-------------------------------------------------------\n";
	$contenidoTXT .= "Nombre : $nombre\n";
	$contenidoTXT .= "País : $pais\n";
	$contenidoTXT .= "Email : $email\n";
	$contenidoTXT .= "Comentarios : $comentarios\n";
	$contenidoTXT .= "-------------------------------------------------------\n";
	
	return( $contenidoTXT );
}


// Cuerpo
/*
$contenidoTXT = "\n\nDatos:\n";
$contenidoTXT .= "-------------------------------------------------------\n";
$contenidoTXT .= "Nombre : {nombre}\n";
$contenidoTXT .= "País : {pais}\n";
$contenidoTXT .= "Email : {email}\n";
$contenidoTXT .= "Comentarios : {comentarios}\n";
$contenidoTXT .= "-------------------------------------------------------\n";
*/

?>
