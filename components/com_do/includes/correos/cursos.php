<?php

function _plantilla( &$recipiente, &$cc, &$bcc, &$sujeto, &$acuserecivo, &$html, &$adjuntos, &$msg ){

	$recipiente		= explode( ",", base64_decode(JRequest::getVar('recipientes',base64_encode(''),'request','string')) );
	//$recipiente		= array();
	//$recipiente[]	= "sebastian@do.cl";
	//$recipiente[]	= "portalchilesomostodos@gmail.com";
	$cc				= NULL;
	$bcc			= NULL;
	$sujeto			= "Inscripción Capacitación On-line";
	$acuserecivo	= NULL;
//	$acuserecivo	= strval( mosGetParam( $_REQUEST, 'Email', '' ) );
	$html			= 0;
	$adjuntos		= NULL;
	//$msg			= "Muchas gracias por su participación, su opinión es muy importante para nosotros";
	
	// datos formulario
	$apellidoM		= JRequest::getVar('apellidoPaterno','','request','string');
	$apellidoP		= JRequest::getVar('apellidoMaterno','','request','string');
	$nombres		= JRequest::getVar('nombres','','request','string');
	$cargo			= JRequest::getVar('cargo','','request','string');
	$run			= JRequest::getVar('run','','request','string');
	$fnacimiento	= JRequest::getVar('fechaNacDia','','request','string').'-'.JRequest::getVar('fechaNacMes','','request','string').'-'.JRequest::getVar('fechaNacAnno','','request','string');
	$sexo			= JRequest::getVar('sexo','','request','string');
	$telefenos		= JRequest::getVar('telefonos','','request','string');
	$email			= JRequest::getVar('email','','request','string');
	$curso			= JRequest::getVar('curso','','request','string');
	$fecha			= JRequest::getVar('fecha','','request','string');
	$horario		= JRequest::getVar('horario','','request','string');
	$lugar			= JRequest::getVar('lugar','','request','string');
	$servicio		= JRequest::getVar('servicio','','request','string');
	$jefatura		= JRequest::getVar('jefatura','','request','string');
	$cargoUnidad	= JRequest::getVar('cargoUnidad','','request','string');
	$fonosUnidad	= JRequest::getVar('telefonosUnidad','','request','string');

	// cuerpo
	$contenidoTXT = "\n\nDatos Participante:\n".
	"-------------------------------------------------------\n".
	"Apellido Paterno : ".$apellidoM."\n".
	"Apellido Materno : ".$apellidoP."\n".
	"Nombres : ".$nombres."\n".
	"Cargo : ".$cargo."\n".
	"RUN : ".$run."\n".
	"Fecha de Nacimiento : ".$fnacimiento."\n".
	"Sexo : ".$sexo."\n".
	"Teléfonos : ".$telefenos."\n".
	"Email : ".$email."\n".
	"Curso en el que se inscribirá : ".$curso."\n".
	"Fecha : ".$fecha."\n".
	"Horario : ".$horario."\n".
	"Lugar de Realización : ".$lugar."\n".
	"-------------------------------------------------------\n".
	"Datos de la Unidad\n".
	"-------------------------------------------------------\n".
	"Servicio : ".$servicio."\n".
	"Jefatura Directa : ".$jefatura."\n".
	"Cargo : ".$cargoUnidad."\n".
	"Teléfonos de la unidad : ".$fonosUnidad."\n".
	"-------------------------------------------------------\n".
	$comentarios."\n"
	;
	
	return( $contenidoTXT );
}

?>
