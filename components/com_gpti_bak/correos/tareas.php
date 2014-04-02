<?php

function _plantilla( &$obj , &$vars )
{	
	global $Itemid;
	
	// cuerpo
	$contenidoTXT = 
	"<br />\t".$vars['titulo']."<br />".
	"-------------------------------------------------------<br />".
	( $vars['introtext']	? $vars['introtext']	: "" ).
	( $vars['introtext']	? "<br />-------------------------------------------------------<br />"	: "" ).
	
	( $obj->TAR_NOMBRE	? "<b>Nombre de la Tarea : </b>".$obj->TAR_NOMBRE."<br />"	: "" ).
	( $obj->TAR_TIPO	? "<b>Tipo :</b> ".$obj->TAT_NOMBRE."<br />"	: "" ).	
	( $obj->TAR_REQ	? "<b>Requerimiento : </b>".$obj->REQUERIMIENTO."<br />"	: "" ).
	( $obj->TAR_FECHA_CREACION	? "<b>Fecha Creaci&oacute;n : </b> ".$obj->FECHA_CREACION."<br />"	: "" ).	
	( $obj->TAR_CREADOR	? "<b>Creador : </b> ".$obj->CREADOR->name."<br />"	: "" ).	
	( $obj->TAR_RECURSO	? "<b>Ejecutor : </b>".$obj->RECURSO->name."<br />"	: "" ).
	
	(( !$obj->TAR_FECHA_INICIO || !$obj->TAR_FECHA_TERMINO || !$obj->TAR_HH_ESTIMADA || !$obj->TAR_FECHA_INICIO_REAL || !$obj->TAR_FECHA_TERMINO_REAL || !$obj->TAR_HH_INFORMADA  ) ? "" :	"-------------------------------------------------------<br />"  ).
	( $obj->TAR_FECHA_INICIO	? "<b>Fecha de Inicio : </b>".$obj->FECHA_INICIO."<br />"	: "" ).
	( $obj->TAR_FECHA_TERMINO	? "<b>Fecha de T&eacute;rmino : </b>".$obj->FECHA_TERMINO."<br />"	: "" ).
	( $obj->TAR_HH_ESTIMADA	? "<b>Horas Hombre Estimadas :</b> ".$obj->TAR_HH_ESTIMADA."<br />"	: "" ).
	"-------------------------------------------------------<br />".
	( $obj->TAR_FECHA_INICIO_REAL	? "<b>Fecha de Inicio Real :</b> ".$obj->FECHA_INICIO_REAL."<br />"	: "" ).	
	( $obj->TAR_FECHA_TERMINO_REAL	? "<b>Fecha de T&eacute;rmino Real :</b> ".$obj->FECHA_TERMINO_REAL."<br />"	: "" ).
	( $obj->TAR_HH_INFORMADA	? "<b>Horas Hombre Informadas  : </b>".$obj->TAR_HH_INFORMADA."<br />"	: "" ).
	
	( $obj->TAR_OBSERVACIONES	? "-------------------------------------------------------<br /><b>Observaciones </b><br /> ".$obj->TAR_OBSERVACIONES."<br />"	: "" ).	
	( $obj->TAR_OBS_EJECUTOR	? "-------------------------------------------------------<br /><b>Observaciones del Ejecutor </b><br /> ".$obj->TAR_OBS_EJECUTOR."<br />"	: "" ).	
	
	( $vars['fulltext']	? "<br />-------------------------------------------------------<br />"	: "" ).
	( $vars['fulltext']	? $vars['fulltext']	: "" ).
	"-------------------------------------------------------<br />".
	"".JURI::base()."index.php?option=com_gpti&Itemid=$Itemid&c=requerimientos&task=ver&REQ_ID=".$obj->TAR_REQ;
	
	return( $contenidoTXT );
}

?>