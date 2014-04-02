<?php

function _plantilla( &$obj , &$vars )
{
	global $Itemid;
	
	if( count($obj->REQ_ANEXOS)):
	$anexos = '';
		foreach( $obj->REQ_ANEXOS as $anexo ): 		
			$anexos .= '- ' . $anexo->ANX_LINK .'<br />'; 
		endforeach;
	endif;
	if( count($obj->REQ_MODULOS)):
	$modulos = '';
		foreach( $obj->REQ_MODULOS as $modulo ): 
			$modulos .= '- ' . $modulo->MOD_NOMBRE .'<br />'; 
		endforeach;
	endif;
	if( count($obj->REQ_AREAS)):
	$areas = '';
		foreach( $obj->REQ_AREAS as $area ): 
			$areas .= '- ' . $area->ARE_NOMBRE .'<br />'; 
		endforeach;
	endif;
	if( count($obj->REQ_VALORES)):
	$valores = '';
		foreach( $obj->REQ_VALORES as $valor ): 
			$valores .= '- ' . $valor->VAS_NOMBRE .'<br />'; 
		endforeach;
	endif;
	if( count($obj->REQ_TAREAS)): 
	$tareas = '';
		foreach( $obj->REQ_TAREAS as $tarea ):
			$tareas .= 'Nombre Tarea : '.$tarea->TAR_NOMBRE.'<br />'; 
			$tareas .= 'HH estimadas : '.$tarea->TAR_HH_ESTIMADA.'<br />';  
			$tareas .= 'Ejecutar : '.$tarea->RECURSO->name.'<br />';  
			$tareas .= 'Tipo de Tarea : '.$tarea->TAT_NOMBRE.'<br />'; 
			$tareas .= 'Fecha de inicio : '.$tarea->FECHA_INICIO.'<br />';  
			$tareas .= 'Fecha de Termino : '.$tarea->FECHA_TERMINO.'<br />';  
			$tareas .= 'Observaciones : '.$tarea->TAR_OBSERVACIONES.'<br />';  
			$tareas .= ( $tarea->TAR_HH_INFORMADA ) ?'HH informadas : '.$tarea->TAR_HH_INFORMADA.'<br />': "";
			$tareas .= ( $tarea->FECHA_INICIO_REAL ) ?'Fecha de inicio Real : '.$tarea->FECHA_INICIO_REAL.'<br />': "";
			$tareas .= ( $tarea->FECHA_TERMINO_REAL ) ?'Fecha de Termino Real : '.$tarea->FECHA_TERMINO_REAL.'<br />': "";   
			$tareas .= ( $tarea->TAR_OBS_EJECUTOR ) ? 'Observaciones del Ejecutor : '.$tarea->TAR_OBS_EJECUTOR.'<br />' : ""; 
			$tareas .= '<br />';
		endforeach;
	endif;
	
	// cuerpo
	$contenidoTXT = 
	"<br />\t".$vars['titulo']."<br />".
	"-------------------------------------------------------<br />".	
	( $vars['introtext']	? $vars['introtext']	: "" ).
	( $vars['introtext']	? "<br />-------------------------------------------------------<br /><br />"	: "" ).
	
	( $obj->REQ_NOMBRE	? "<b>Nombre del Requerimiento : </b> ".$obj->REQ_NOMBRE."<br />"	: "" ).
	( $obj->REQ_DRU	? "<b>Nro. DRU : </b> ".$obj->REQ_DRU."<br />"	: "" ).
	( $obj->REQ_NRO_INTERNO	? "<b>Nro. Interno : </b> ".$obj->REQ_NRO_INTERNO."<br />"	: "" ).
	( $obj->REQ_PROYECTO	? "<b>Proyecto : </b> ".$obj->PROYECTO."<br />"	: "" ).
	( $obj->REQ_OBJETIVO	? "<b>Objetivo : </b> <br /> ".$obj->REQ_OBJETIVO."<br />"	: "" ).
	( $obj->REQ_PRIORIDAD	? "<b>Prioridad : </b> ".$obj->REQ_PRIORIDAD."<br />"	: "" ).
	( $obj->TIPO	? "<b>Tipo :</b> ".$obj->TIPO."<br />"	: "" ).
	( $obj->REQ_DESCRIPCION	? "<b>Descripci&oacute;n : </b><br /> ".$obj->REQ_DESCRIPCION."<br />"	: "" ).	
	( $obj->ESTADO	? "<b>Estado : </b>".$obj->ESTADO."<br />"	: "" ).	
	( $obj->INDICE_DECISION && $obj->REQ_FASE > 1	? "<b>Indice de Decisi&oacute;n : </b> ".$obj->INDICE_DECISION."<br />"	: "" ).	
	//( $obj->REQ_CLASIFICACION	? "Clasificaci&oacute;n <br /> ".$obj->REQ_CLASIFICACION."<br />"	: "" ).
	( (!$obj->REQ_FECHA_CREACION || !$obj->REQ_FECHA_APRUEBA || !$obj->REQ_FECHA_MODIFICACION || !$obj->REQ_FECHA_ENTREGA ) ? "" : "-------------------------------------------------------<br />" ).
	
	( $obj->REQ_FECHA_CREACION	? "<b>Fecha de Creaci&oacute;n :</b> ".$obj->FECHA_CREACION."<br />"	: "" ).	
	( $obj->REQ_FECHA_APRUEBA	? "<b>Fecha Aprobaci&oacute;n :</b> ".$obj->FECHA_APRUEBA."<br />"	: "" ).	
	( $obj->REQ_FECHA_MODIFICACION	? "<b>Fecha Modificaci&oacute;n :</b> ".$obj->FECHA_MODIFICACION."<br />"	: "" ).	
	( $obj->REQ_FECHA_ENTREGA	? "<b>Fecha de entrega esperada : </b>".$obj->FECHA_ENTREGA."<br />"	: "" ).
	
	( (!$obj->REQ_USUARIO || !$obj->REQ_USUARIO_APRUEBA || !$obj->REQ_PROVEEDOR || !$obj->REQ_GERENCIA ) ? "" : "-------------------------------------------------------<br />" ).
	( $obj->REQ_USUARIO_MODIFICA	? "<b>Usuario Modificador :</b> ".$obj->USUARIO_MODIFICA->name."<br />"	: "" ).
	( $obj->USUARIO->name	? "<b>Usuario Solicitante :</b> ".$obj->USUARIO->name."<br />"	: "" ).
	( $obj->REQ_USUARIO_APRUEBA	? "<b>Usuario Aprobaci&oacute;n :</b> ".$obj->USUARIO_APRUEBA->name."<br />"	: "" ).	
	( $obj->REQ_PROVEEDOR	? "<b>Proveedor : </b> ".$obj->PROVEEDOR."<br />"	: "" ).	
	( $obj->GERENCIA	? "<b>Gerencia :</b> ".$obj->GERENCIA."<br />"	: "" ).	
	
	( (!$anexos || !$tareas || !$modulos || !$areas || !$valores ) ? "" : "-------------------------------------------------------<br />" ).
	( $anexos	? "<br /><b>Anexos </b><br />".$anexos."<br />"	: "" ).
	( $tareas	? "<br /><b>Tareas </b><br />".$tareas."<br />"	: "" ).
	( $modulos	? "<br /><b>Modulos que Afectan </b><br />".$modulos."<br />"	: "" ).
	( $areas	? "<br /><b>&Aacute;reas de Desarrollo de Soluciones </b><br />".$areas."<br />"	: "" ).
	( $valores	? "<br /><b>Dimensiones de Valor de Soluci&oacute;n </b><br />".$valores."<br />"	: "" ).
	
	( $vars['fulltext']	? "<br />-------------------------------------------------------<br />"	: "" ).
	( $vars['fulltext']	? $vars['fulltext']	: "" ).
	"<br />-------------------------------------------------------<br />".
	"".JURI::base()."index.php?option=com_gpti&Itemid=$Itemid&c=requerimientos&task=ver&REQ_ID=".$obj->REQ_ID;
	
	return( $contenidoTXT );
}

?>