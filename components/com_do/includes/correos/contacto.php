<?php

function _plantilla( &$recipiente, &$cc, &$bcc, &$sujeto, &$acuserecivo, &$html, &$adjuntos, &$msg ){

	$recipiente		= explode( ",", base64_decode(JRequest::getVar('recipientes',base64_encode(''),'request','string')) );
	//$recipiente		= array();
	//$recipiente[]	= "sebastian@do.cl";
	//$recipiente[]	= "portalchilesomostodos@gmail.com";
	$cc				= NULL;
	$bcc			= NULL;
	$sujeto			= "Contacto Intranet";
	$acuserecivo	= NULL;
//	$acuserecivo	= strval( mosGetParam( $_REQUEST, 'Email', '' ) );
	$html			= 0;
	$adjuntos		= NULL;
	//$msg			= "Muchas gracias por su participación, su opinión es muy importante para nosotros";
	
	// datos formulario
	$nombre			= JRequest::getVar('nombre','','request','string');
	$apellidos		= JRequest::getVar('apellidos','','request','string');
	$rut			= JRequest::getVar('rut','','request','string');
	$direccion		= JRequest::getVar('direccion','','request','string');
	$telefono		= JRequest::getVar('telefono','','request','string');
	$email			= JRequest::getVar('email','','request','string');
	$contactar		= JRequest::getVar('contactar','','request','string');
	$comentarios	= JRequest::getVar('comentarios','','request','string');

	// cuerpo
	$contenidoTXT = "\n\nAntecedentes:\n".
	"-------------------------------------------------------\n".
	"Nombre : ".$nombre."\n".
	"Apellidos : ".$apellidos."\n".
	"Rut : ".$rut."\n".
	"Dirección : ".$direccion."\n".
	"Teléfono : ".$telefono."\n".
	"E-mail : ".$email."\n".
	"Contarctar por : ".$contactar."\n".
	"-------------------------------------------------------\n".
	"Comentarios\n".
	"-------------------------------------------------------\n".
	$comentarios."\n"
	;
	
	return( $contenidoTXT );
}

?>
