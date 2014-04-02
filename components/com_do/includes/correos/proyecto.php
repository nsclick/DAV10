<?php

function _plantilla( &$recipiente, &$cc, &$bcc, &$sujeto, &$acuserecivo, &$html, &$adjuntos, &$msg ){

	$recipiente		= array();
	//$recipiente[]	= "portalchilesomostodos@gmail.com";
	$recipiente[]	= "sebastian@do.cl";
	$cc				= NULL;
	$bcc			= NULL;
	$sujeto			= "Nuevo Proyecto Recibido";
	$acuserecivo	= NULL;
//	$acuserecivo	= strval( mosGetParam( $_REQUEST, 'Email', '' ) );
	$html			= 0;
	$msg			= "fondos";
	$adjuntos = NULL;
	if($_FILES["anexos"]["tmp_name"]){
		$adjuntos = array();
		$adjuntos[0]["ruta"]	= $_FILES["anexos"]["tmp_name"];
		$adjuntos[0]["nombre"]	= $_FILES["anexos"]["name"];
	}
	
	// datos formulario
	$asociacion		 		= JRequest::getVar('asociacion','','request','string');
	$pais		 			= JRequest::getVar('pais','','request','string');
	$ciudad		 			= JRequest::getVar('ciudad','','request','string');
	$juridica				= JRequest::getVar('juridica','','request','string');
	$obtencion				= JRequest::getVar('obtencion','','request','string');
	$representante			= JRequest::getVar('representante','','request','string');
	$email					= JRequest::getVar('email','','request','string');
	$recipiente[] = $email;
	$telefono				= JRequest::getVar('telefono','','request','string');
	$domicilio				= JRequest::getVar('domicilio','','request','string');
	$titulo					= JRequest::getVar('titulo','','request','string');
	$lineapostulacion		= JRequest::getVar('lineapostulacion','','request','string');
	$fechaInicioActividad	= JRequest::getVar('fechaInicioActividad','','request','string');
	$fechaTerminoActividad	= JRequest::getVar('fechaTerminoActividad','','request','string');
	$grupo					= JRequest::getVar('grupo','','request','string');
	$resumen				= JRequest::getVar('resumen','','request','string');
	$objetivo				= JRequest::getVar('objetivo','','request','string');
	$antecedentes			= JRequest::getVar('antecedentes','','request','string');
	$descripcion			= JRequest::getVar('descripcion','','request','string');
	$metodologia			= JRequest::getVar('metodologia','','request','string');
	$propositos				= JRequest::getVar('propositos','','request','string');
	$resultados				= JRequest::getVar('resultados','','request','string');
	$actividades = array();
	$flag=1;
	for($i=1;$flag==1;$i++){
		if(isset($_POST["actividad$i"])){
			$a						= NULL;
			eval("\$actividad 		= JRequest::getVar('actividad".$i."','','request','string');");
			eval("\$fechainicio 	= JRequest::getVar('ganttFechaInicio".$i."','','request','string');");
			eval("\$fechatermino 	= JRequest::getVar('ganttFechaTermino".$i."','','request','string');");
			eval("\$encargado 		= JRequest::getVar('ganttEncargado".$i."','','request','string');");
			eval("\$costo 			= JRequest::getVar('costo".$i."','','request','string');");
			eval("\$aporte 			= JRequest::getVar('aporte".$i."','','request','string');");
			eval("\$solicitado 		= JRequest::getVar('solicitado".$i."','','request','string');");
			$a->actividad 			= $actividad;
			$a->fechainicio 		= $fechainicio;
			$a->fechatermino 		= $fechatermino;
			$a->encargado 			= $encargado;
			$a->costo 				= $costo;
			$a->aporte 				= $aporte;
			$a->solicitado 			= $solicitado;
			$actividades[] 			= $a;
		}else{
			$flag=0;
		}
	}
	$costoTotal 			= JRequest::getVar('hdTotalProyecto','','request','string');
	$localTotal				= JRequest::getVar('hdAporteLocal','','request','string');
	$aporteSolicitado 		= JRequest::getVar('hdAporteSolicitado','','request','string');

	// cuerpo
	$contenidoTXT = "\n\nDatos del proyecto:\n".
	"-------------------------------------------------------\n".
	"Asociación y/o agrupación ejecutora : ".$asociacion."\n".
	"País : ".$pais."\n".
	"Ciudad : ".$ciudad."\n".
	"Nº Personalidad Jurídica : ".$juridica."\n".
	"Año obtención : ".$obtencion."\n".
	"Nombre del representante legal : ".$representante."\n".
	"Correo Electrónico : ".$email."\n".
	"Teléfono : ".$telefono."\n".
	"Domicilio : ".$domicilio."\n".
	"Título del Proyecto : ".$titulo."\n".
	"Línea de Postulación : ".$lineapostulacion."\n".
	"Fecha de Inicio : ".$fechaInicioActividad."\n".
	"Fecha de Término : ".$fechaTerminoActividad."\n".
	"Grupo objetivo : ".$grupo."\n".
	"Resumen del Proyecto : ".$resumen."\n".
	"1.0 Objetivo general : ".$objetivo."\n".
	"2.0 Antecedentes y justificación : ".$antecedentes."\n".
	"2.1 Descripción : ".$descripcion."\n".
	"2.2 Metodología de trabajo : ".$metodologia."\n".
	"3.0 Propósito : ".$propositos."\n".
	"4.0 Resultados esperados : ".$resultados."\n".
	"\n-------------------------------------------------------\n".
	"Carta Gantt y Presupuesto COSTO\n".
	"-------------------------------------------------------\n";
	foreach($actividades as $act){
		$contenidoTXT .= "\n".
		"Actividad : ".$act->actividad."\n".
		"Fecha de Inicio : ".$act->fechainicio."\n".
		"Fecha de Término : ".$act->fechatermino."\n".
		"Encargado : ".$act->encargado."\n".
		"Costo Total : ".$act->costo."\n".
		"Aporte Local : ".$act->aporte."\n".
		"Aporte Solicitado : ".$act->solicitado."\n"
		;
	}
	$contenidoTXT .= " \n".
	"-------------------------------------------------------\n".
	"Costos\n".
	"-------------------------------------------------------\n".
	"TOTAL DEL PROYECTO : ".$costoTotal."\n".
	"APORTE LOCAL : ".$localTotal."\n".
	"APORTE SOLICITADO : ".$aporteSolicitado."\n".
	"-------------------------------------------------------\n"
	;
	
	return( $contenidoTXT );
}

?>
