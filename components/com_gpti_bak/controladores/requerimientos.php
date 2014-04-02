<?php
/**
 * @version		$Id: requerimientos.php 2011-05-26 Sebastián García Truan
 * @package		Joomla
 * @subpackage	DO
 * @autor		Diseño Objetivo www.do.cl
 * @copyright	Copyright (C) 2006 - 2010 Diseño Objetivo. Todos los derechos reservados.
 * @license		LICENCIA_DO.php
 */

	/**************************************/
	/*                                    */
	/*          Diseño Objetivo           */
	/*     			www.do.cl    	  	  */
	/*   		 contacto@do.cl  		  */
	/*      Fono: (56-02) 228 13 91       */
	/*                                    */
	/**************************************/

// sin acceso directo
defined( '_JEXEC' ) or die( 'Restricted access' ); 
defined( '_DO_GPTI' ) or die( 'El acceso directo a este archivo no está permitido.' );

jimport( 'joomla.application.component.controller' );

/**
 * @package		Joomla
 * @subpackage	DO
 */
class GPTIControllerRequerimientos extends JController  
{
	/**
	 * Constructor*
	 */
	function __construct()
	{
		parent::__construct( array() );
		$this->registerTask( 'editar',				'ingresar' );
		$this->registerTask( 'asignar',				'cambio_prioridad_solicitar' );
		
		$this->registerTask( 'ingresar_proveedor',	'ingresar_submit' );
		$this->registerTask( 'aceptar',				'ingresar_submit' );
		
		$this->registerTask( 'aceptar_prueba',		'prueba_aprueba' );
		$this->registerTask( 'rechaza_prueba',		'prueba_aprueba' );
		
		$this->registerTask( 'pasaraprueba',		'pasar_a_prueba' );
		
		$this->registerTask( 'tarea_rechaza',		'tarea_submit' );
		$this->registerTask( 'tarea_aprueba',		'tarea_submit' );

		$this->registerTask( 'buscar',				'display' );
		$this->registerTask( 'prueba_rechaza',		'planificar_aprueba' );
		$this->registerTask( 'rechaza_planificar',	'planificar_aprueba' );
		
		$this->registerTask( 'revisar',				'ver' );
		
		$this->registerTask( 'check_gerencia',		'tarea_submit' );
		$this->registerTask( 'check_ejecutor',		'tarea_submit' );
		
		$this->registerTask( 'acepta_ger',			'acepta_gerencia' );
		$this->registerTask( 'rechaza_ger',			'acepta_gerencia' );
		$this->registerTask( 'anular_ger',			'acepta_gerencia' );
		
		
		
		$this->registerTask( 'cambiosprioridad_rechazar',		'cambiosprioridad_aprobar' );
	}

	function display() 
	{
		//ob_start();
		global $mainframe, $Itemid;
		$session	=& JFactory::getSession();
		$GPTIuser	= $session->get( 'GPTI_user' );
		
		$lists								= array();
		
		$lists['perfil-Admin']				= $GPTIuser->USR_PERFIL == 1;
		$lists['perfil-Gerente']			= $GPTIuser->USR_PERFIL == 2;
		$lists['perfil-Gerencia']			= $GPTIuser->USR_PERFIL == 3;
		$lists['perfil-Usuario']			= $GPTIuser->USR_PERFIL == 4;
		$lists['perfil-Gerente-Proveedor']	= $GPTIuser->USR_PERFIL == 5;
		$lists['perfil-Ejecutor']			= $GPTIuser->USR_PERFIL == 6;
				
		$REQ		=& JTable::getInstance('requerimientos', 'GPTI');
		
		$REQ->REQ_PROYECTO			= $mainframe->getUserStateFromRequest( $context.'filtro_proyecto',		'filtro_proyecto',			0,			'int' 		);
		$REQ->REQ_ESTADO			= $mainframe->getUserStateFromRequest( $context.'filtro_estado',		'filtro_estado',			0,			'int' 		);
		$REQ->REQ_NOMBRE			= $mainframe->getUserStateFromRequest( $context.'filtro_nombre',		'filtro_nombre',			'',			'string' 	);
		$REQ->REQ_GERENCIA			= $mainframe->getUserStateFromRequest( $context.'filtro_gerencia',		'filtro_gerencia',			0,			'int' 		);
		$REQ->REQ_PLAZO				= $mainframe->getUserStateFromRequest( $context.'filtro_plazos',		'filtro_plazos',			0,			'int' 		);
		
		if( $lists['perfil-Usuario'] || $lists['perfil-Gerencia'] ):
			$REQ->REQ_GERENCIA		=	$GPTIuser->USR_GERENCIA;
			$REQ->REQ_FASE_DESDE	= 1;
			$REQ->REQ_FASE_HASTA	= 6;
		elseif( $lists['perfil-Ejecutor'] || $lists['perfil-Gerente-Proveedor'] ):
			$REQ->REQ_FASE_DESDE	= 4;
			$REQ->REQ_FASE_HASTA	= 6;
			$REQ->REQ_PROVEEDOR		= $GPTIuser->USR_PROVEEDOR;
		endif;
		
		
		//GPTIuser->USR_PROVEEDOR
		$REQ->REQ_DRU				= $mainframe->getUserStateFromRequest( $context.'filtro_n_dru',			'filtro_n_dru',				'',			'string' 	);
		$REQ->REQ_ENCARGADO			= $mainframe->getUserStateFromRequest( $context.'filtro_req_encargado',	'filtro_req_encargado',		0,			'int' 		);
		//$REQ->REQ_CLASIFICACION		= $mainframe->getUserStateFromRequest( $context.'filtro_clasificacion',	'filtro_clasificacion',		0,			'int' 		);
		$REQ->REQ_NRO_INTERNO		= $mainframe->getUserStateFromRequest( $context.'filtro_n_interno',		'filtro_n_interno',			'',			'string' 	);
		$REQ->REQ_FECHA_DESDE		= $mainframe->getUserStateFromRequest( $context.'filtro_fecha_desde',	'filtro_fecha_desde',		'',			'string' 	);
		$REQ->REQ_FECHA_HASTA		= $mainframe->getUserStateFromRequest( $context.'filtro_fecha_hasta',	'filtro_fecha_hasta',		'',			'string' 	);

		$REQ->REQ_NOMBRE			= ( $REQ->REQ_NOMBRE == 'Nombre del proyecto' )? '' : $REQ->REQ_NOMBRE ;
		$REQ->REQ_DRU				= ( $REQ->REQ_DRU == 'Nº DRU' )? '' : $REQ->REQ_DRU ;
		$REQ->REQ_NRO_INTERNO		= ( $REQ->REQ_NRO_INTERNO == 'Nº Interno' )? '' : $REQ->REQ_NRO_INTERNO ;
		$REQ->REQ_FECHA_DESDE		= ( $REQ->REQ_FECHA_DESDE == 'Ingreso Desde' )? '' : $REQ->REQ_FECHA_DESDE ;
		$REQ->REQ_FECHA_HASTA		= ( $REQ->REQ_FECHA_HASTA == 'Ingreso Hasta' )? '' : $REQ->REQ_FECHA_HASTA ;
			
		$rows 						= $REQ->buscar();

		$lists['id-perfil'] 				= $GPTIuser->PFL_ID;
		$lists['select-proyecto']			= GPTIHelperHtml::SelectReqsProyectos('filtro_proyecto', $REQ->REQ_PROYECTO );
		$lists['text-proyecto']				= GPTIHelperHtml::Text('filtro_nombre','Nombre del proyecto', $REQ->REQ_NOMBRE );
		$lists['select-tipo']				= GPTIHelperHtml::SelectReqsTipos('filtro_tipo', $REQ->REQ_TIPO );
		$lists['select-prioridad']			= GPTIHelperHtml::SelectReqsPrioridades('filtro_prioridad', $REQ->REQ_PRIORIDAD );
		if( !($lists['perfil-Usuario'] || $lists['perfil-Gerencia']) ) :
			$lists['select-gerencia']			= GPTIHelperHtml::SelectReqsGerencia('filtro_gerencia', $REQ->REQ_GERENCIA );
		endif;
		$lists['select-estado']				= GPTIHelperHtml::SelectReqsEstado('filtro_estado', $REQ->REQ_ESTADO );
		
		$lists['select-responsables-modulos']= GPTIHelperHtml::SelectReqEncargados('filtro_req_encargado', $REQ->REQ_ENCARGADO );
		//$lists['select-clasificacion']		= GPTIHelperHtml::SelectReqsClasificacion('filtro_clasificacion', $REQ->REQ_CLASIFICACION );
		$lists['calendario-fecha-desde']	= GPTIHelperHtml::Calendario( 'filtro_fecha_desde', 'Ingreso Desde', $REQ->REQ_FECHA_DESDE, NULL, '%Y-%m-%d', 'class="fecha"' );
		$lists['calendario-fecha-hasta']	= GPTIHelperHtml::Calendario( 'filtro_fecha_hasta', 'Ingreso Hasta', $REQ->REQ_FECHA_HASTA, NULL, '%Y-%m-%d', 'class="fecha"' );
		
		$lists['text-numero-int']			= GPTIHelperHtml::Text('filtro_n_interno','Nº Interno',$REQ->REQ_NRO_INTERNO,' id="filtro_n_interno"');
		$lists['text-numero-dru']			= GPTIHelperHtml::Text('filtro_n_dru','Nº DRU',$REQ->REQ_DRU,' id="filtro_n_dru"');

//		function radio( $nombre='', $titulo='', $valor=null, $id=null, $attribs=null )
		$lists['radio-todos']			 	= GPTIHelperHtml::radio('filtro_plazos','Todos',0,'todos', ( $REQ->REQ_PLAZO == 0 ? 'checked="checked"' : '' ) );
		$lists['radio-dentro-plazo']		= GPTIHelperHtml::radio('filtro_plazos','Dentro del Plazo',1,'plazos', ( $REQ->REQ_PLAZO == 1 ? 'checked="checked"' : '' ) );
		$lists['radio-atrasados']			= GPTIHelperHtml::radio('filtro_plazos','Atrasados',2,'atrasados', ( $REQ->REQ_PLAZO == 2 ? 'checked="checked"' : '' ) );
		$lists['radio-comite']				= GPTIHelperHtml::radio('filtro_plazos','Comité',3,'comite', ( $REQ->REQ_PLAZO == 3 ? 'checked="checked"' : '' ) );

		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'requerimientos.php');
		//ob_end_clean();
		GPTIVistaRequerimientos::display( $lists , $rows );
	}
	
	function ingresar()
	{
		if( !GPTIHelperACL::check('req_ingresar') ) :
			GPTIHelperError::Raise( 'Usted no tiene permiso para realizar esta operación' );
		endif;
		
		global $mainframe, $Itemid;
		$session	=& JFactory::getSession();
		$GPTIuser	=& $session->get( 'GPTI_user', null );
		
		JHTML::_('behavior.modal');
		JHTML::_('behavior.tooltip');
		
		$REQ		=& JTable::getInstance('requerimientos', 'GPTI');
		$REQ->get( JRequest::getInt('REQ_ID', 0 ) );	
		
		$lists								= array();

		$lists['req-id']					= $REQ->REQ_ID ;
		
		$lists['msj']						= base64_decode(JRequest::getVar('msj',base64_encode(''),'REQUEST'));
		
		$lists['select-proyecto']			= GPTIHelperHtml::SelectReqsProyectos('REQ_PROYECTO', $REQ->REQ_PROYECTO,'style="width:260px; margin-right:5px;"');
		$lists['text-nombre']				= GPTIHelperHtml::Text('REQ_NOMBRE','Nombre Corto del Requerimiento', $REQ->REQ_NOMBRE,'style="width:260px; margin-right:5px;"').' <strong><small><small>(*)</small></small></strong>';
		$lists['calendario-fecha-entrega']	= GPTIHelperHtml::Calendario( 'REQ_FECHA_ENTREGA', 'Fecha de Entrega Esperada', $REQ->REQ_FECHA_ENTREGA ,NULL,'%Y-%m-%d','style="width:140px; "' ).'  <strong><small>(*)</small></strong>';
		/*
		$lists['textarea-objetivo']			= GPTIHelperHtml::TextArea( 'REQ_OBJETIVO', 'Objetivos', $REQ->REQ_OBJETIVO );
		$lists['textarea-desc']				= GPTIHelperHtml::TextArea( 'REQ_DESCRIPCION', 'Descripción', $REQ->REQ_DESCRIPCION );
		*/
		$lists['textarea-proposito']		= GPTIHelperHtml::TextArea( 'REQ_PROPOSITO', 'Propósitos', $REQ->REQ_PROPOSITO ). ' <strong><small>(*)</small></strong>';
		$lists['textarea-diagnostico']		= GPTIHelperHtml::TextArea( 'REQ_DIAGNOSTICO', 'Diagnóstico', $REQ->REQ_DIAGNOSTICO ). ' <strong><small>(*)</small></strong>';
		$lists['textarea-capacidades']		= GPTIHelperHtml::TextArea( 'REQ_CAPACIDADES', 'Capacidades', $REQ->REQ_CAPACIDADES ). ' <strong><small>(*)</small></strong>';
		
		$lists['select-tipo']				= GPTIHelperHtml::SelectReqsTipos('REQ_TIPO', $REQ->REQ_TIPO). ' <strong><small>(*)</small></strong>';
		$lists['select-gerencia']			= GPTIHelperHtml::SelectReqsGerencia('REQ_GERENCIA', $REQ->REQ_GERENCIA ). ' <strong><small>(*)</small></strong>';
		$lists['select-clasificacion']		= GPTIHelperHtml::SelectReqsClasificacion('REQ_CLASIFICACION', $REQ->REQ_CLASIFICACION);
		
		$info_p 							= 'Describa en forma general las necesidades del usuario';
		$lists['info-proposito'] 			= JHTML::tooltip( $info_p, 'Propósitos', 'info-davila.png', '', '');
		$info_d								= 'Describa el problema en forma general e informal, cuyo fin es diagnosticar la problemática';
		$lists['info-diagnostico'] 			= JHTML::tooltip( $info_d , 'Diagnóstico', 'info-davila.png', '', '');
		$info_c								= 'Describa el proceso con palabras simplesm que debería soportar el requerimiento. A partir del mismo relato se identifican, informalmente, los requerimientos que para su posterior formalización y desglose. Enumérelos';
		$lists['info-capacidades'] 			= JHTML::tooltip( $info_c , 'Capacidades', 'info-davila.png', '', '');

		//$lists['select-ejecutor']			= GPTIHelperHtml::SelectTareaEjecutores('REQ_PROVEEDOR', $REQ->REQ_PROVEEDOR);
		//$lists['select-prioridad']		= GPTIHelperHtml::SelectReqsPrioridades('REQ_PRIORIDAD', $REQ->REQ_PRIORIDAD);
		
		$lists['archivos-anexos']			= $REQ->REQ_ANEXOS;
		$lists['select-file']				= GPTIHelperHtml::selectFile( 'REQ_ANEXOS[]', $REQ->REQ_VALORES, NULL, 'size="50"' );

		$lists['multiple-modulos']			= GPTIHelperHtml::MultiSelecs( 'modulos', 'REQ_MODULOS[]' , $REQ->REQ_MODULOS, ' style="width:280px; height:120px;"' );
		$lists['multiple-areas']			= GPTIHelperHtml::MultiSelecs( 'areas', 'REQ_AREAS[]', $REQ->REQ_AREAS, ' style="width:280px; height:120px;"' );
		$lists['multiple-valores']			= GPTIHelperHtml::MultiSelecs( 'valores', 'REQ_VALORES[]', $REQ->REQ_VALORES, ' style="width:280px; height:120px;"' );
		

		$url = JRoute::_('index.php?c=requerimientos&task=decision&tmpl=componente&Itemid'.$Itemid );
		
		$lists['boton-indice']				= GPTIHelperHtml::Link( 'Ver Tabla Índices', $url, 'modal', 'rel="{handler: \'iframe\', size: {x: 440, y: 550}}"' ); 
		
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'requerimientos.php');
		GPTIVistaRequerimientos::ingresar( $REQ, $lists );
	}	
	
	function ver()
	{
		global $mainframe, $Itemid;

		$session	=& JFactory::getSession();
		$GPTIuser	= $session->get( 'GPTI_user' );

		$lists								= array();
		$lists['ACL_req_informar'] 			= GPTIHelperACL::check('req_informar');
			
		$REQ		=& JTable::getInstance('requerimientos', 'GPTI');
		 
		$REQ->get(  JRequest::getInt( 'REQ_ID' ) );

		$lists['select-proveedor']			= GPTIHelperHtml::SelectReqProveedores('REQ_PROVEEDOR', NULL);
		$lists['textarea-observacion']		= GPTIHelperHtml::TextArea( 'OBSERVACION', 'Observacion', NULL );
	
		$lists['id-Usuario']				= $GPTIuser->USR_ID;
		$lists['perfil-Admin']				= $GPTIuser->USR_PERFIL == 1;
		$lists['perfil-Gerente']			= $GPTIuser->USR_PERFIL == 2;
		$lists['perfil-Gerencia']			= $GPTIuser->USR_PERFIL == 3;
		$lists['perfil-Usuario']			= $GPTIuser->USR_PERFIL == 4;
		$lists['perfil-Gerente-Proveedor']	= $GPTIuser->USR_PERFIL == 5;
		$lists['perfil-Ejecutor']			= $GPTIuser->USR_PERFIL == 6;

		$lists['perfil-Usuario-Fase']		= $lists['perfil-Usuario']  && ( $REQ->REQ_FASE == 1 );
		$lists['perfil-Gerencia-Fase']		= $lists['perfil-Gerencia'] && ( $REQ->REQ_FASE < 3 ) ;
		$lists['perfil-Admin-Fase']			= $lists['perfil-Admin']    && ( $REQ->REQ_FASE < 4 ) ; 

		$lists['recurso-proveedor']			= $GPTIuser->USR_ID;

		//$REQ->USUARIO = $GPTIuser->joomla;
		
		$lists['msj']	= base64_decode(JRequest::getVar('msj',base64_encode(''),'REQUEST'));
				
		if( $lists['perfil-Gerente-Proveedor'] || $lists['perfil-Ejecutor'] ) :
			$REQ->REQ_PRIORIDAD		= $REQ->REQ_PRIORIDAD_PROV;
		endif;

		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'requerimientos.php');
		GPTIVistaRequerimientos::ver( $lists, $REQ );
	}
	
	function planificar()
	{
		
		global $mainframe, $Itemid;
		$session	=& JFactory::getSession();
		$GPTIuser	=& $session->get( 'GPTI_user', null );
		
		$REQ		=& JTable::getInstance('requerimientos', 'GPTI');
		$REQ->get( JRequest::getInt('REQ_ID', 0) );
				
		$lists								= array();

		$lists['perfil-Admin']				= $GPTIuser->USR_PERFIL == 1;
		$lists['perfil-Gerente']			= $GPTIuser->USR_PERFIL == 2;
		$lists['perfil-Gerencia']			= $GPTIuser->USR_PERFIL == 3;
		$lists['perfil-Usuario']			= $GPTIuser->USR_PERFIL == 4;
		$lists['perfil-Gerente-Proveedor']	= $GPTIuser->USR_PERFIL == 5;
		$lists['perfil-Ejecutor']			= $GPTIuser->USR_PERFIL == 6;
		
		$lists['text-nombre']				= GPTIHelperHtml::Text('TAR_NOMBRE[]','Nombre de la Tarea',NULL,'class="ancho_xl"' );
		$lists['text-estimada-hh']			= GPTIHelperHtml::Text('TAR_HH_ESTIMADA[]','Estimación HH',NULL,'class="ancho_xl" onkeydown="javascript:return numbersOnly(event);"' );
		$lists['select-ejecutor']			= GPTIHelperHtml::SelectTareaEjecutores('TAR_RECURSO[]',NULL,'class="ancho_xl"', $GPTIuser->USR_PROVEEDOR  );
		$lists['select-tipo']				= GPTIHelperHtml::SelectTareaTipos('TAR_TIPO[]',NULL,'class="ancho_xl"' );
		$lists['calendario-fecha-inicio']	= GPTIHelperHtml::Calendario('TAR_FECHA_INICIO[]', 'Fecha Inicio',NULL,NULL,'%Y-%m-%d', 'class="inputclass"' );
		$lists['calendario-fecha-termino']	= GPTIHelperHtml::Calendario('TAR_FECHA_TERMINO[]', 'Fecha Termino',NULL,NULL,'%Y-%m-%d', 'class="inputclass"' );
		$lists['textarea-observaciones']	= GPTIHelperHtml::TextArea('TAR_OBSERVACIONES[]', 'Observaciones', NULL,NULL,"3","25" );	
		
		foreach( $REQ->REQ_TAREAS as $i => $TAR  ):
		
			$lists['text-nombre-arreglo'][$i]				= GPTIHelperHtml::Text('TAR_NOMBRE[]','Nombre de la Tarea',$TAR->TAR_NOMBRE,'class="ancho_xl"' );
			$lists['text-estimada-hh-arreglo'][$i]			= GPTIHelperHtml::Text('TAR_HH_ESTIMADA[]','Estimación HH',$TAR->TAR_HH_ESTIMADA,'class="ancho_xl" onkeydown="javascript:return numbersOnly(event);"' );
			$lists['select-ejecutor-arreglo'][$i]			= GPTIHelperHtml::SelectTareaEjecutores('TAR_RECURSO[]',$TAR->TAR_RECURSO,'class="ancho_xl"' );
			$lists['select-tipo-arreglo'][$i]				= GPTIHelperHtml::SelectTareaTipos('TAR_TIPO[]',$TAR->TAR_TIPO,'class="ancho_xl"' );
			$lists['calendario-fecha-inicio-arreglo'][$i]	= GPTIHelperHtml::Calendario('TAR_FECHA_INICIO[]', 'Fecha Inicio', $TAR->TAR_FECHA_INICIO ,NULL,'%Y-%m-%d', 'class="inputclass"' );
			$lists['calendario-fecha-termino-arreglo'][$i]	= GPTIHelperHtml::Calendario('TAR_FECHA_TERMINO[]', 'Fecha Termino', $TAR->TAR_FECHA_TERMINO ,NULL,'%Y-%m-%d', 'class="inputclass"' );
			$lists['textarea-observaciones-arreglo'][$i]	= GPTIHelperHtml::TextArea('TAR_OBSERVACIONES[]', 'Observaciones', $TAR->TAR_OBSERVACIONES , NULL,"3","25" );	
		
		endforeach;
			
		
		//$lists['tarea-check']				=  'check_gerencia'  ;
	
		$lists['text-numero-int']			= GPTIHelperHtml::Text('REQ_NRO_INTERNO','Nro. Interno', $REQ->REQ_NRO_INTERNO );

		$lists['msj']	= base64_decode(JRequest::getVar('msj',base64_encode(''),'REQUEST'));
	
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'requerimientos.php');
		GPTIVistaRequerimientos::planificar( $lists, $REQ );
	}
		
	function cambiosprioridad()
	{
		global $mainframe, $Itemid;
		
		$lists['ACL_req_cp'] 			= GPTIHelperACL::check('req_cp');
		$lists['ACL_req_cp_aprobar'] 	= GPTIHelperACL::check('req_cp_aprobar');
		
		if( !$lists['ACL_req_cp'] && !$lists['ACL_req_cp_aprobar'] ) :
			GPTIHelperError::Raise( 'Usted no tiene permiso para estar aquí' );
		endif;
		
		if( !$lists['ACL_req_cp_aprobar'] ) :
			$this->cambiosprioridad_gerencia();
			return;
		endif;

		$lists['msj']	= base64_decode(JRequest::getVar('msj',base64_encode(''),'REQUEST'));
	
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'requerimientos.php');
		GPTIVistaRequerimientos::cambiosprioridad( $lists, $rows, $row );
	}
	
	function cambiosprioridad_gerencia()
	{	
		global $mainframe, $Itemid;
		
		$lists['ACL_req_cp'] 			= GPTIHelperACL::check('req_cp');
		$lists['ACL_req_cp_aprobar'] 	= GPTIHelperACL::check('req_cp_aprobar');
		
		if( !$lists['ACL_req_cp'] && !$lists['ACL_req_cp_aprobar'] ) :
			GPTIHelperError::Raise( 'Usted no tiene permiso para estar aquí' );
		endif;
		
		
		$session			=& JFactory::getSession();
		$GPTIuser			=& $session->get( 'GPTI_user', null );
		
		$REQ				=& JTable::getInstance('requerimientos', 'GPTI');
		$lists['msj']		= '';

		if( $lists['ACL_req_cp'] ) :

			$cp_enproceso		= false;
			$row				= new stdClass();
			$cp					= $REQ->cambioprioridades();
			if( $cp	&& is_array($cp) && count($cp) ) :
				foreach( $cp as $k => $v ) :
					if( $v->RCP_ESTADO == 'I' ) :
						//$row			= $REQ->cambioprioridad( (int)$v->RCP_ID );
						$cp_enproceso	= true;
						$lists['msj']	= '<ul class="incorrecto"><li>Ya existe una solicitud de cambio prioridad en proceso.</li></ul>';
					endif;
				endforeach;
			endif;
		
			$rows				= array();
			
			if( !$cp_enproceso ) :
				$REQ->REQ_GERENCIA		= $GPTIuser->USR_GERENCIA;
				$REQ->REQ_FASE_DESDE	= 2;
				$REQ->REQ_FASE_HASTA	= 5;
				$rows 					= $REQ->buscar();
			endif;
			
		elseif( $lists['ACL_req_cp_aprobar'] ) :
			$RCP_ID					= JRequest::getInt('RCP_ID',0);
			$lists['p_gerencia']	= GPTIHelperHtml::SelectCambiosPrioridad( 'RCP_ID', $RCP_ID, 'onchange="javascript:this.form.task.value=\'cambiosprioridad_gerencia\'; this.form.submit();"' );
			$rows					= $REQ->cambioprioridad( (int)$_POST['RCP_ID'] );
			if( !$RCP_ID ) :
				$lists['msj']	= 'Para procesar un cambio de prioridad, favor seleccionar aquí:';
			elseif( $rows && is_array($rows) && count($rows) ) :
				
			else :
				$lists['msj']	= 'No hay nuevas solicitudes de cambio de prioridad';
			endif;
		endif;
		
		$msj				= base64_decode(JRequest::getVar('msj',base64_encode(''),'REQUEST'));
		if( $msj ) :
			$lists['msj']	= "<ul class=\"correcto\"><li>$msj</li></ul>";
		endif;

		//if( $lists['ACL_req_cp'] || $lists['ACL_req_cp_aprobar'] ) :
			require_once(JPATH_COMPONENT.DS.'vistas'.DS.'requerimientos.php');
			GPTIVistaRequerimientos::cambiosprioridad_gerencia( $lists, $rows, $row );
		//endif;
	}
	
	function cambiosprioridad_proveedor()
	{
		global $mainframe, $Itemid;
		
		$lists['ACL_req_cp_aprobar'] 	= GPTIHelperACL::check('req_cp_aprobar');
		
		if( !$lists['ACL_req_cp_aprobar'] ) :
			GPTIHelperError::Raise( 'Usted no tiene permiso para estar aquí' );
		endif;
		
		$REQ				=& JTable::getInstance('requerimientos', 'GPTI');
		$lists['msj']		= '';
		
		$rows				= array();
		
		$REQ->REQ_PROVEEDOR			= JRequest::getInt('REQ_PROVEEDOR',0);
		if( $REQ->REQ_PROVEEDOR ) :
			$REQ->REQ_FASE_DESDE	= 4;
			$REQ->REQ_FASE_HASTA	= 5;
			$rows 					= $REQ->buscar("ORDER BY REQS.REQ_PRIORIDAD_PROV ASC, REQS.REQ_FECHA_CREACION DESC");
		endif;
		
		$lists['proveedores']	= GPTIHelperHtml::SelectReqProveedores( 'REQ_PROVEEDOR', $REQ->REQ_PROVEEDOR, 'onchange="javascript:this.form.task.value=\'cambiosprioridad_proveedor\'; this.form.submit();"' );
		
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'requerimientos.php');
		GPTIVistaRequerimientos::cambiosprioridad_proveedor( $lists, $rows, $row );
	}
	
	function cambiosprioridad_comite()
	{
		global $mainframe, $Itemid;
		
		$lists['ACL_req_cp_aprobar'] 	= GPTIHelperACL::check('req_cp_aprobar');
		
		if( !$lists['ACL_req_cp_aprobar'] ) :
			GPTIHelperError::Raise( 'Usted no tiene permiso para estar aquí' );
		endif;
		
		$REQ				=& JTable::getInstance('requerimientos', 'GPTI');
		$lists['msj']		= '';
		
		$rows				= array();
		
		$REQ->REQ_FASE_DESDE	= 2;
		$REQ->REQ_FASE_HASTA	= 5;
		$rows 					= $REQ->buscar("ORDER BY REQS.REQ_PRIORIDAD_CTE ASC, REQS.REQ_FECHA_CREACION DESC");
		
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'requerimientos.php');
		GPTIVistaRequerimientos::cambiosprioridad_comite( $lists, $rows, $row );
	}
	
	function cambiosprioridad_submit()
	{
		global $mainframe, $Itemid;
		
		$lists['ACL_req_cp'] 			= GPTIHelperACL::check('req_cp');
		
		if( !$lists['ACL_req_cp'] ) :
			GPTIHelperError::Raise( 'Usted no tiene permiso para estar aquí' );
		endif;
		
		$session			=& JFactory::getSession();
		$GPTIuser			=& $session->get( 'GPTI_user', null );
		
		$REQ				=& JTable::getInstance('requerimientos', 'GPTI');
		
		$cid				= JRequest::getVar('cid',array(),'request','array');
		$cid				= implode( ",", $cid );
		if( $REQ->cambioprioridades_ingresar( $cid ) ) :
			$vars['rte'] 		= $mainframe->getCfg( 'mailfrom' );
			$vars['dst'] 		= "";
			foreach( GPTIHelperUsuarios::getUsuarios(array('perfil'=>1)) as $ai => $admin ) :
				$vars['dst'] 	.= ($ai ? ',' : '').$admin['joomla']->get('email');
			endforeach;
			//$vars['cc'] 		= null;
			$vars['sujeto'] 	= '[GPTI] Solicitud Cambio Prioridad #'.$REQ->RCP_ID;
			$vars['html'] 		= false;
			$vars['detalle'] 	= 'El/La Gerente '.$GPTIuser->joomla->name.' ha ingresado una solicitud de cambio de prioridad #'.$REQ->RCP_ID.' para la gerencia '.$GPTIuser->GER_NOMBRE;
			GPTIHelperCorreo::Encolar($vars);

			$msj	= base64_encode('La solicitud de cambio prioridad #'.$REQ->RCP_ID.' fue ingresada con éxito. No podrá solicitar otro cambio de prioridad hasta que se cierre el actual');
			$mainframe->redirect( "index.php?option=com_gpti&Itemid=$Itemid&msj=$msj#gpti_msj" );
		endif;
	}
	
	function cambiosprioridad_aprobar()
	{
		//cambiosprioridad_rechazar
		global $mainframe, $Itemid;
		
		$lists['ACL_req_cp_aprobar'] 		= GPTIHelperACL::check('req_cp_aprobar');
		
		if( !$lists['ACL_req_cp_aprobar'] ) :
			GPTIHelperError::Raise( 'Usted no tiene permiso para estar aquí' );
		endif;
		
		$session			=& JFactory::getSession();
		$GPTIuser			=& $session->get( 'GPTI_user', null );
		
		$REQ				=& JTable::getInstance('requerimientos', 'GPTI');
		
		$p_id				= JRequest::getVar('RCP_ID',0,'request','int');
		$rows				= $REQ->cambioprioridad( $p_id );
		$task				= JRequest::getCmd('task');
		$p_estado			= $task == 'cambiosprioridad_aprobar' ? 'A' : 'R';
		
		if( $REQ->cambiosprioridad_aprobar( $p_id, $p_estado ) ) :
			$vars['rte'] 		= $mainframe->getCfg( 'mailfrom' );
			$vars['dst'] 		= "";
			foreach( GPTIHelperUsuarios::getUsuarios(array('perfil'=>3,'gerencia'=>$rows[0]->RCP_GERENCIA)) as $ai => $admin ) :
				$vars['dst'] 	.= ($ai ? ',' : '').$admin['joomla']->get('email');
			endforeach;
			//$vars['cc'] 		= null;
			$vars['sujeto'] 	= '[GPTI] Solicitud Cambio Prioridad #'.$p_id.' '.($p_estado=='A'?'APROBADA':'RECHAZADA');
			$vars['html'] 		= false;
			$vars['detalle'] 	= 'El/La Administrador(a) '.$GPTIuser->joomla->name.' ha '.($p_estado=='A'?'APROBADO':'RECHAZADO').' su solicitud de cambio prioridad #'.$p_id;
			GPTIHelperCorreo::Encolar($vars);
		
			$msj	= base64_encode('La solicitud de cambio prioridad gerencia #'.$p_id.' ha sido '.($p_estado=='A'?'aprobada':'rechazada').' con éxito');
			$mainframe->redirect( "index.php?option=com_gpti&Itemid=$Itemid&msj=$msj#gpti_msj" );
		endif;
	}
	
	function cambiosprioridad_aprobar_prov()
	{
		global $mainframe, $Itemid;
		
		$lists['ACL_req_cp_aprobar'] 		= GPTIHelperACL::check('req_cp_aprobar');
		
		if( !$lists['ACL_req_cp_aprobar'] ) :
			GPTIHelperError::Raise( 'Usted no tiene permiso para estar aquí' );
		endif;
		
		$session			=& JFactory::getSession();
		$GPTIuser			=& $session->get( 'GPTI_user', null );
		
		$REQ				=& JTable::getInstance('requerimientos', 'GPTI');
		
		$proveedor			= JRequest::getInt('REQ_PROVEEDOR',0);
		$cid				= JRequest::getVar('cid',array(),'request','array');
		$cid				= implode( ",", $cid );
		if( $REQ->cambioprioridades_ingresar_prov( $cid, $proveedor ) ) :
			$vars['rte'] 		= $mainframe->getCfg( 'mailfrom' );
			$vars['dst'] 		= "";
			foreach( GPTIHelperUsuarios::getUsuarios(array('perfil'=>5,'proveedor'=>$proveedor)) as $gp => $gerenteproveedor ) :
				$vars['dst'] 	.= ($gp ? ',' : '').$gerenteproveedor['joomla']->get('email');
			endforeach;
			//$vars['cc'] 		= null;
			$vars['sujeto'] 	= '[GPTI] Cambio Prioridad #'.$REQ->RCP_ID;
			$vars['html'] 		= false;
			$vars['detalle'] 	= 'El/La Administrador(a) '.$GPTIuser->joomla->name.' ha efectuado un cambio de prioridad #'.$REQ->RCP_ID.' en los requerimientos asignados a usted.';
			GPTIHelperCorreo::Encolar($vars);

			$msj	= base64_encode('La solicitud de cambio prioridad proveedor #'.$REQ->RCP_ID.' ha sido ingresada y aprobada con éxito');
			$mainframe->redirect( "index.php?option=com_gpti&Itemid=$Itemid&msj=$msj#gpti_msj" );
		endif;
		
	}
	
	function cambiosprioridad_aprobar_cte()
	{
		global $mainframe, $Itemid;
		
		$lists['ACL_req_cp_aprobar'] 		= GPTIHelperACL::check('req_cp_aprobar');
		
		if( !$lists['ACL_req_cp_aprobar'] ) :
			GPTIHelperError::Raise( 'Usted no tiene permiso para estar aquí' );
		endif;
		
		$session			=& JFactory::getSession();
		$GPTIuser			=& $session->get( 'GPTI_user', null );
		
		$REQ				=& JTable::getInstance('requerimientos', 'GPTI');
		
		$cid				= JRequest::getVar('cid',array(),'request','array');
		$cid				= implode( ",", $cid );
		if( $REQ->cambioprioridades_ingresar_cte( $cid ) ) :
			$vars['rte'] 		= $mainframe->getCfg( 'mailfrom' );
			$vars['dst'] 		= "";
			foreach( GPTIHelperUsuarios::getUsuarios(array('perfil'=>1)) as $ai => $admins ) :
				$vars['dst'] 	.= ($ai ? ',' : '').$admins['joomla']->get('email');
			endforeach;
			//$vars['cc'] 		= null;
			$vars['sujeto'] 	= '[GPTI] Cambio Prioridad Comité #'.$REQ->RCP_ID;
			$vars['html'] 		= false;
			$vars['detalle'] 	= 'El/La Administrador(a) '.$GPTIuser->joomla->name.' ha efectuado un cambio de prioridad comité #'.$REQ->RCP_ID;
			GPTIHelperCorreo::Encolar($vars);

			$msj	= base64_encode('La solicitud de cambio prioridad comité #'.$REQ->RCP_ID.' ha sido ingresada y aprobada con éxito');
			$mainframe->redirect( "index.php?option=com_gpti&Itemid=$Itemid&msj=$msj#gpti_msj" );
		endif;
	}

	function acepta_gerencia()
	{
		global $mainframe, $Itemid;
		$session	=& JFactory::getSession();
		$GPTIuser	=& $session->get( 'GPTI_user', null );
		$vars = array();
		
		if( !$GPTIuser->USR_PERFIL == 3 ) :
			GPTIHelperError::Raise( 'Usted no tiene permiso para realizar esta operación' );
		endif;
			
		$REQ		=& JTable::getInstance('requerimientos', 'GPTI');
		$REQ->get( JRequest::getInt('REQ_ID',0) );
	
		if( JRequest::getVar('task') == 'acepta_ger' ):
			$msj	= base64_encode('Para aceptar el requerimiento, debe complementar la información');
			$mainframe->redirect( "index.php?option=com_gpti&Itemid=$Itemid&task=editar&REQ_ID=".$REQ->REQ_ID."&msj=$msj" );
			return;
			/*$REQ->REQ_ESTADO 	= 4; //aceptado gerencia
			$REQ->REQ_FASE 		= 2; //fase 2
			$REQ->REQ_FECHA_MODIFICACION=  date("Y-m-d") ; 
			
			$vars['sujeto'] 	= '[GPTI] Se ha aceptado el Requerimiento '.$REQ->REQ_NOMBRE .' ( DRU Nro.'.$REQ->REQ_DRU.') ' ;	
			$vars['introtext'] 	= 'La gerencia '.$GPTIuser->GER_NOMBRE.' ha aceptado este Requerimiento' ;

			$msj	= base64_encode('Gerencia ha aceptado el requerimiento ('.$REQ->REQ_DRU.') con éxito');*/
		endif;
		
		if( JRequest::getVar('task') == 'rechaza_ger' ):	
			$REQ->REQ_ESTADO 	= 3; //rechazado gerencia
			$REQ->REQ_FASE 		= 1; //fase 1
			$REQ->REQ_FECHA_MODIFICACION=  date("Y-m-d") ; 
			
			$vars['sujeto'] 	= '[GPTI] Se ha rechazado el Requerimiento '.$REQ->REQ_NOMBRE .' ( DRU Nro.'.$REQ->REQ_DRU.') ' ;	
			$vars['introtext'] 	= 'La gerencia '.$GPTIuser->GER_NOMBRE.' ha rechazado este Requerimiento' ;
			$msj	= base64_encode('Gerencia ha rechazado el requerimiento ('.$REQ->REQ_DRU.') con éxito');
		endif;			
		
		if( JRequest::getVar('task') == 'anular_ger' ):	
			$REQ->REQ_ESTADO 	= 1; //anulado gerencia
			$REQ->REQ_FASE 		= 1; //fase 1
			$REQ->REQ_FECHA_MODIFICACION=  date("Y-m-d") ; 
			
			$vars['sujeto'] 	= '[GPTI] Se ha anulado el Requerimiento '.$REQ->REQ_NOMBRE .' ( DRU Nro.'.$REQ->REQ_DRU.') ' ;	
			$vars['introtext'] 	= 'La gerencia '.$GPTIuser->GER_NOMBRE.' ha anulado este Requerimiento' ;
			$msj	= base64_encode('Gerencia ha anulado el requerimiento ('.$REQ->REQ_DRU.') con éxito');
		endif;			
				
		if( $REQ->save() ):
		
			$REQ->get(  $REQ->REQ_ID  );
			$datos['uid']		= $REQ->REQ_USUARIO;
			//$datos['gerencia'] 	= $GPTIuser->USR_GERENCIA;
			//$datos['proveedor']	= $GPTIuser->USR_PERFIL; 
				
			$obs				= JRequest::getVar('OBSERVACION',"");
			$obs				= $obs == "" || strtoupper($obs) == "OBSERVACION" || strtoupper($obs) == "OBSERVACIÓN" ? "No se ingresaron motivos." : $obs;
			$vars['fulltext'] 	= "<b>Motivo :</b><br />$obs";

			$USRS = GPTIHelperUsuarios::getUsuarios( $datos );
			
			$emails				= '';
			foreach( $USRS as $i => $USR ):
				$emails			.= ( $i ? ',' : '' ) . $USR['joomla']->get('email');
			endforeach;

			$vars['rte'] 		= $GPTIuser->joomla->email ;
			$vars['dst'] 		= $emails;
			$vars['cc'] 		= $GPTIuser->joomla->email ;
			$vars['bcc'] 		= '' ;
			
			$vars['detalle'] 	= '' ;
			$vars['html'] 		= 'S' ;
			$vars['titulo'] 	= $REQ->REQ_NOMBRE .' ( DRU Nro.'.$REQ->REQ_DRU.') ' ;
			
			$plantilla			= 'requerimiento';
			
			$tmpl				= JPATH_BASE.DS.'components'.DS.'com_gpti'.DS.'correos'.DS.$plantilla.'.php';		
			if( file_exists( $tmpl ) ) :
				include_once( $tmpl );
				$vars['detalle'] 	= _plantilla( $REQ , $vars );
				GPTIHelperCorreo::Encolar( $vars );
			endif;

		endif;
		
		$mainframe->redirect( "index.php?option=com_gpti&Itemid=$Itemid&task=ver&REQ_ID=".$REQ->REQ_ID."&msj=$msj" );
	}
	
	
	function decision()
	{	
		global $mainframe, $Itemid;
			
		$REQ			=& JTable::getInstance('requerimientos', 'GPTI');	
		$tabla 			= $REQ->getTablaDecision();

		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'requerimientos.php');
		GPTIVistaRequerimientos::decision( $tabla );
	}	
	
	function cerrar_proveedor()
	{
 		if( !GPTIHelperACL::check('req_cerrar_proveedor') ) :
			GPTIHelperError::Raise( 'Usted no tiene permiso para realizar esta operación' );
		endif;
		$vars[] = array();
		
		global $mainframe, $Itemid;
		$session	=& JFactory::getSession();
		$GPTIuser	=& $session->get( 'GPTI_user', null );
		
		$REQ		=& JTable::getInstance('requerimientos', 'GPTI');
		$REQ->get( JRequest::getInt('REQ_ID',0) );
		$REQ->bind( $_POST );
	
		$REQ->REQ_ESTADO 	= 13; //cierre proveedor
		$REQ->REQ_FASE 		= 6; //fase 6
		$REQ->REQ_FECHA_MODIFICACION=  date("Y-m-d") ; 

		if( $REQ->save() ):
			$msj	= base64_encode('El requerimiento ('.$REQ->REQ_DRU.') ha cerrado el Proveedor con éxito');

			$datos['perfil'] 	= 5;
			//$datos['gerencia'] 	= $GPTIuser->USR_GERENCIA;
			$datos['proveedor']	= $REQ->REQ_PROVEEDOR; 	

			$USRS = GPTIHelperUsuarios::getUsuarios( $datos );
			
			foreach( $USRS as $i => $USR ):
				 $emails = ($i ? ',' : '' ).$USR['joomla']->email;
			endforeach;
 				
		
			$vars['introtext'] 	= 'Se ha Cerrador al Proveedor en este Requerimiento' ;

			$vars['rte'] 		= $GPTIuser->joomla->email ;
			$vars['dst'] 		= $emails ;
			$vars['cc'] 		= $GPTIuser->joomla->email ;
			$vars['bcc'] 		= '' ;

			$vars['sujeto'] 	= '[GPTI] '.$REQ->REQ_NOMBRE .' ( DRU Nro. '.$REQ->REQ_DRU.') ' ;
			$vars['detalle'] 	= '' ;
			$vars['html'] 		= 'S' ;
			$vars['titulo'] 	= $REQ->REQ_NOMBRE .' ( DRU Nro. '.$REQ->REQ_DRU.') ' ;
			
			$plantilla			= 'requerimiento';
			
			$tmpl				= JPATH_BASE.DS.'components'.DS.'com_gpti'.DS.'correos'.DS.$plantilla.'.php';		
			if( file_exists( $tmpl ) ) :
				include_once( $tmpl );
				$vars['detalle'] 	= _plantilla( $REQ , $vars );
				GPTIHelperCorreo::Encolar( $vars );
			endif;

		endif;
		$mainframe->redirect( "index.php?option=com_gpti&Itemid=$Itemid&task=ver&REQ_ID=".$REQ->REQ_ID."&msj=$msj" );
	}
	
	function editar_check()
	{
		if( $errores = GPTIHelperValidacion::formIngreso() ) :
			$texto	= 'El formulario presenta los siguientes errores : <br />';
			header('Content-type: application/json');
			?>
			{
					"error": "1",
					"errormsj": "<?php echo $texto . implode( '<br />' , $errores ); ?>"
			}
			<?php
			exit(0);
		endif;		
		
		header('Content-type: application/json');
		?>
		{
				"error": "0",
                "errormsj": "nose"
		}
		<?php
		exit(0); 		
	}

	function editar_submit()
	{		
 		if( !GPTIHelperACL::check('req_ingresar') ) :
			GPTIHelperError::Raise( 'Usted no tiene permiso para realizar esta operación' );
		endif;
		$vars[] = array();
		
		global $mainframe, $Itemid;
		$session	=& JFactory::getSession();
		$GPTIuser	=& $session->get( 'GPTI_user', null );
		
		$REQ		=& JTable::getInstance('requerimientos', 'GPTI');
		$REQ->get( JRequest::getInt('REQ_ID',0 ) );
		$REQ->REQ_AREAS			= array();
		$REQ->REQ_VALORES		= array();
		$REQ->REQ_MODULOS		= array();
		$REQ->bind( $_POST );	
		
		// Usuario
		if( $GPTIuser->USR_PERFIL == 4 ):
		
			$REQ->REQ_FASE 		= 1; //fase 1
			$REQ->REQ_ESTADO 	= 2; //ingresado
			$REQ->REQ_FECHA_MODIFICACION=  date("Y-m-d") ; 
			
			$datos['perfil'] 	= 3;
			$datos['gerencia'] 	= $GPTIuser->USR_GERENCIA;
			//$datos['proveedor']	= $GPTIuser->USR_PERFIL; 	
			
			$vars['introtext'] 	= 'Se ha modificado este Requerimiento' ;
			$vars['fulltext'] 	= '' ;	
		
		// GERENCIA
		elseif( $GPTIuser->USR_PERFIL == 3 ):
		
			$REQ->REQ_FASE 		= 2; //fase 2
			$REQ->REQ_ESTADO 	= 4; //aceptado gerencia
			$REQ->REQ_FECHA_MODIFICACION=  date("Y-m-d") ; 
			
			$datos['perfil'] 	= 1;
			//$datos['gerencia'] 	= $GPTIuser->USR_GERENCIA;
			//$datos['proveedor']	= $GPTIuser->USR_PERFIL; 	
			
			$vars['introtext'] 	= 'Se ha modificado este Requerimiento' ;
			$vars['fulltext'] 	= '' ;	
			
		// ADMIN
		elseif( $GPTIuser->USR_PERFIL == 1 ):
		
			$REQ->REQ_FASE 		= 3; //fase 2
			$REQ->REQ_ESTADO 	= 5; //pendiente
			$REQ->REQ_FECHA_MODIFICACION=  date("Y-m-d") ; 
			
			$datos['perfil'] 	= 3;
			$datos['gerencia'] 	= $REQ->REQ_GERENCIA;
			//$datos['proveedor']	= $GPTIuser->USR_PERFIL; 	
			
			$vars['introtext'] 	= 'Se ha Complementado este Requerimiento' ;
			$vars['fulltext'] 	= '' ;	
			
		endif;
			
		$REQ->REQ_USUARIO_MODIFICA 	= $GPTIuser->USR_ID; //modificado
	
		if( $REQ->save() ):
		
			$REQ->get( $REQ->REQ_ID );
			$msj	= base64_encode('El requerimiento ('.$REQ->REQ_DRU.') ha sido modificado con éxito');
			
			$USRS = GPTIHelperUsuarios::getUsuarios( $datos );
			
			$emails = '';
			foreach( $USRS as $i => $USR ):
				 $emails .= ($i ? ',' : '' ).$USR['joomla']->get('email');
			endforeach;
			
			$datosCC['perfil'] 	= 1;
			$USRScc = GPTIHelperUsuarios::getUsuarios( $datosCC );
			
			$emailsCC = '';
			foreach( $USRScc as $i => $USRcc ):
				 $emailsCC .= ($i ? ',' : '' ).$USRcc['joomla']->get('email');
			endforeach;

			$vars['rte'] 		= $GPTIuser->joomla->email ;
			$vars['dst'] 		= $emails;
			$vars['cc'] 		= $emailsCC;
			$vars['bcc'] 		= '' ;

			$vars['sujeto'] 	= '[GPTI] '.$REQ->REQ_NOMBRE .' ( DRU Nro. '.$REQ->REQ_DRU.') ' ;
			//$vars['sujeto'] 	= 'Se ha modificado el requerimiento'.$REQ->REQ_NOMBRE .' ( DRU Nro. '.$REQ->REQ_DRU.') ' ;
			$vars['detalle'] 	= '' ;
			$vars['html'] 		= 'S' ;
			$vars['titulo'] 	= $REQ->REQ_NOMBRE .' ( DRU Nro. '.$REQ->REQ_DRU.') ' ;
			
			$plantilla			= 'requerimiento';
			
			$tmpl				= JPATH_BASE.DS.'components'.DS.'com_gpti'.DS.'correos'.DS.$plantilla.'.php';		
			if( file_exists( $tmpl ) ) :
				include_once( $tmpl );
				$vars['detalle'] 	= _plantilla( $REQ , $vars );
				GPTIHelperCorreo::Encolar( $vars );
			endif;
			
		endif;
		
		$mainframe->redirect( "index.php?option=com_gpti&Itemid=$Itemid&task=ver&REQ_ID=".$REQ->REQ_ID."&msj=$msj" );
	}
	function ingresar_check()
	{
		if( $errores = GPTIHelperValidacion::formIngreso() ) :
			$texto	= 'El formulario presenta los siguientes errores : <br />';
			header('Content-type: application/json');
			?>
			{
					"error": "1",
					"errormsj": "<?php echo $texto . implode( '<br />' , $errores ); ?>"
			}
			<?php
			exit(0);
		endif;		
		
		header('Content-type: application/json');
		?>
		{
				"error": "0",
                "errormsj": "nose"
		}
		<?php
		exit(0); 		
	}
	
	function ingresar_submit()
	{		
 		if( !GPTIHelperACL::check('req_ingresar') ) :
			GPTIHelperError::Raise( 'Usted no tiene permiso para realizar esta operación' );
		endif;
		$vars	 	= array();
		$datos 		= array();
		
		global $mainframe, $Itemid;
		$session	=& JFactory::getSession();
		$GPTIuser	=& $session->get( 'GPTI_user', null );
		
		$REQ		=& JTable::getInstance('requerimientos', 'GPTI');
		$REQ->get( JRequest::getInt('REQ_ID',0) );
		$REQ->bind( $_POST );	
		
		// USUARIO
		if( $GPTIuser->USR_PERFIL == 4 ):
		
			$REQ->REQ_FASE 		= 1; //fase 1
			$REQ->REQ_GERENCIA	= $GPTIuser->USR_GERENCIA;
			$REQ->REQ_ESTADO 	= 2; //ingresado
			$REQ->REQ_FECHA_MODIFICACION=  date("Y-m-d") ; 
			
			$datos['perfil'] 	= 3;
			$datos['gerencia'] 	= $GPTIuser->USR_GERENCIA;
			//$datos['proveedor']	= $GPTIuser->USR_PERFIL; 	
			
			$vars['introtext'] 	= 'Se ha ingresado un nuevo Requerimiento' ;
			$vars['fulltext'] 	= '' ;

		// GERENCIA
		elseif( $GPTIuser->USR_PERFIL == 3 ):
		
			$REQ->REQ_FASE 		= 2; //fase 2
			$REQ->REQ_GERENCIA	= $GPTIuser->USR_GERENCIA;
			$REQ->REQ_ESTADO 	= 4; //aceptado gerencia 
			$REQ->REQ_FECHA_MODIFICACION=  date("Y-m-d") ; 
		
			$datos['perfil'] 	= 1;
			//$datos['gerencia'] 	= $GPTIuser->USR_GERENCIA;
			//$datos['proveedor']	= $GPTIuser->USR_PERFIL; 	
			
			$vars['introtext'] 	= 'Se ha ingresado un nuevo Requerimiento' ;
			$vars['fulltext'] 	= '' ;

		// ADMIN
		elseif( $GPTIuser->USR_PERFIL == 1 ):
		
			$REQ->REQ_FASE 		= 3; //fase 2
			$REQ->REQ_ESTADO 	= 5; //pendiente 
			$REQ->REQ_FECHA_MODIFICACION=  date("Y-m-d") ; 
			
			$datos['perfil'] 	= 1;
			//$datos['gerencia'] 	= $GPTIuser->USR_GERENCIA;
			//$datos['proveedor']	= $GPTIuser->USR_PERFIL; 
			if( JRequest::getVar('task') == 'ingresar_proveedor' ) //asignar proveedor
			{
				
				$REQ->REQ_FASE 		= 4; //fase 4
				$REQ->REQ_ESTADO 	= 6; //derivado 
				$REQ->REQ_FECHA_MODIFICACION=  date("Y-m-d") ; 
				
				$datos['perfil'] 	= 5;
				//$datos['gerencia'] 	= $GPTIuser->USR_GERENCIA;
				$datos['proveedor']	= $_POST['REQ_PROVEEDOR']; 
				
				$vars['introtext'] 	= 'Se ha asignado un proveedor a este requerimiento' ;
				$vars['fulltext'] 	= '' ;
				
			}		
		endif;
		if( $REQ->save() ):
		
			$REQ->get(  $REQ->REQ_ID  );
			if( JRequest::getVar('task') == 'ingresar_proveedor' ) //asignar proveedor
			{
				$msj	= base64_encode('El requerimiento ('.$REQ->REQ_DRU.') se le ha asignado un proveedor con éxito');
			}else{
				$msj	= base64_encode('El requerimiento ('.$REQ->REQ_DRU.') ha sido ingresado con éxito');
			}


			$USRS				= GPTIHelperUsuarios::getUsuarios( $datos );
			$emails				= '';
			foreach( $USRS as $i => $USR ):
				 $emails		.= ($i ? ',' : '' ).$USR['joomla']->get('email');
			endforeach;

			$vars['rte'] 		= $GPTIuser->joomla->email ;
			$vars['dst'] 		= $emails ;
			$vars['cc'] 		= $GPTIuser->joomla->email ;
			$vars['bcc'] 		= '' ;
			$vars['sujeto'] 	= '[GPTI] '.$REQ->REQ_NOMBRE .' ( DRU Nro. '.$REQ->REQ_DRU.') ' ;
			$vars['detalle'] 	= '' ;
			$vars['html'] 		= 'S' ;
			$vars['titulo'] 	= $REQ->REQ_NOMBRE .' ( DRU Nro. '.$REQ->REQ_DRU.') ' ;
			
			$plantilla			= 'requerimiento';
			$tmpl				= JPATH_BASE.DS.'components'.DS.'com_gpti'.DS.'correos'.DS.$plantilla.'.php';		
			if( file_exists( $tmpl ) ) :
				include_once( $tmpl );				
				$vars['detalle'] 	= _plantilla( $REQ , $vars );
				GPTIHelperCorreo::Encolar( $vars );
			endif;
		
		endif;

		$mainframe->redirect( "index.php?option=com_gpti&Itemid=$Itemid&task=ver&REQ_ID=".$REQ->REQ_ID."&msj=$msj" );
	}
	
	function prueba_aprueba()
	{
 		if( !GPTIHelperACL::check('req_prueba_aprueba') ) :
			GPTIHelperError::Raise( 'Usted no tiene permiso para realizar esta operación' );
		endif;
		$vars[] = array();
		
		global $mainframe, $Itemid;
		$session	=& JFactory::getSession();
		$GPTIuser	=& $session->get( 'GPTI_user', null );
		
		$REQ		=& JTable::getInstance('requerimientos', 'GPTI');
		$REQ->get( JRequest::getInt('REQ_ID',0) );
		$REQ->bind( $_POST );
	
		$REQ->REQ_ESTADO			= ( JRequest::getVar('task') == 'rechaza_prueba' ) ? 11 /* prueba rechazada */ : 12 /* prueba acepta */ ;
		$REQ->REQ_FASE 				= 6; //fase 6
		$REQ->REQ_FECHA_MODIFICACION=  date("Y-m-d") ; 
		$REQ->REQ_USUARIO_APRUEBA 	= $GPTIuser->USR_ID;
		$REQ->REQ_FECHA_APRUEBA		=  date("Y-m-d") ; 
	
		if( $REQ->save() ):
			$REQ->get(  $REQ->REQ_ID  );
		
			$msj	= base64_encode('La prueba del requerimiento ('.$REQ->REQ_DRU.') se ha '.( JRequest::getVar('task') == 'rechaza_prueba' ? 'rechazado':'aceptado').' con éxito');
			
			if( JRequest::getVar('task') == 'rechaza_prueba' ) :
				$USRS				= GPTIHelperUsuarios::getUsuarios( array('perfil'=>5,'proveedor'=>$REQ->REQ_PROVEEDOR) );
				$emails				= '';
				foreach( $USRS as $i => $USR ):
					 $emails		.= ( $i ? ',' : '' ) . $USR['joomla']->get('email');
				endforeach;
			else :
				$emails				= '';
				$USRS				= GPTIHelperUsuarios::getUsuarios( array('perfil'=>5,'proveedor'=>$REQ->REQ_PROVEEDOR) );
				foreach( $USRS as $i => $USR ):
					 $emails		.= ( $i ? ',' : '' ) . $USR['joomla']->get('email');
				endforeach;
				
				$emailsCC			= '';
				$USRScc				= GPTIHelperUsuarios::getUsuarios( array('perfil'=>1) );
				foreach( $USRScc as $i => $USRcc ):
					 $emailsCC		.= ( $emailsCC != '' ? ',' : '' ) . $USRcc['joomla']->get('email');
				endforeach;

				$USRScc				= GPTIHelperUsuarios::getUsuarios( array('gerencia'=>$REQ->REQ_GERENCIA) );
				foreach( $USRScc as $i => $USRcc ):
					 $emailsCC		.= ( $emailsCC != '' ? ',' : '' ) . $USRcc['joomla']->get('email');
				endforeach;

			endif;
			

			/*
			$datosp = array();
			$datosg = array();
			$datosa = array();
			
			$emails = '' ; 
			$datosp['perfil'] 	= 5;
			//$datos['gerencia'] 	= $GPTIuser->USR_GERENCIA;
			$datosp['proveedor']	= $REQ->REQ_PROVEEDOR; 		
			$PROVS = GPTIHelperUsuarios::getUsuarios( $datosp );
			foreach( $PROVS as $i => $PROV ):
				 $emails .= ($i ? ',' : '' ).$PROV['joomla']->email;
			endforeach;
			
			$datogs['perfil'] 	= 3;
			$datos['gerencia'] 	= $GPTIuser->USR_GERENCIA;
			//$datosg['proveedor']	= $REQ->REQ_PROVEEDOR; 		
			$GERS = GPTIHelperUsuarios::getUsuarios( $datosg );
			
			foreach( $GERS as $i => $GER ):
				 $emails .= ( $emails != '' ? ',' : '' ).$GER['joomla']->email;
			endforeach;

			$datosa['perfil'] 	= 1;
			//$datos['gerencia'] 	= $GPTIuser->USR_GERENCIA;
			//$datosa['proveedor']	= $REQ->REQ_PROVEEDOR; 		
			$USRS = GPTIHelperUsuarios::getUsuarios( $datosa );
			
			foreach( $ADMS as $i => $ADM ):
				 $emails .= ( $emails != '' ? ',' : '' ).$ADM['joomla']->email;
			endforeach;
			*/

			$vars['rte'] 		= $GPTIuser->joomla->email ;
			$vars['dst'] 		= $emails ;
			$vars['cc'] 		= $emailsCC ;
			$vars['bcc'] 		= '' ;
			$vars['sujeto'] 	= '[GPTI] '.$REQ->REQ_NOMBRE .' ( DRU Nro. '.$REQ->REQ_DRU.') ' ;
			$vars['detalle'] 	= '' ;
			$vars['html'] 		= 'S' ;
			$vars['titulo'] 	= $REQ->REQ_NOMBRE .' ( DRU Nro. '.$REQ->REQ_DRU.') ' ;
			$vars['introtext'] 	= 'Se ha pasado a producción este requerimiento' ;
				
			$obs				= JRequest::getVar('OBSERVACION',"");
			$obs				= $obs == "" || strtoupper($obs) == "OBSERVACION" || strtoupper($obs) == "OBSERVACIÓN" ? "No se ingresaron motivos." : $obs;
			$vars['fulltext'] 	= "<b>Motivo :</b><br />$obs";

				
			$plantilla			= 'requerimiento';
			
			$tmpl				= JPATH_BASE.DS.'components'.DS.'com_gpti'.DS.'correos'.DS.$plantilla.'.php';		
			if( file_exists( $tmpl ) ) :
				include_once( $tmpl );
				$vars['detalle'] 	= _plantilla( $REQ , $vars );
				GPTIHelperCorreo::Encolar( $vars );
			endif;	
		
		endif;
		$mainframe->redirect( "index.php?option=com_gpti&Itemid=$Itemid&task=ver&REQ_ID=".$REQ->REQ_ID."&msj=$msj" );
	}
	
	function pasar_a_prueba()
	{		
 		if( !GPTIHelperACL::check('req_prueba') ) :
			GPTIHelperError::Raise( 'Usted no tiene permiso para realizar esta operación' );
		endif;
		$vars[] = array();
		
		global $mainframe, $Itemid;
		$session	=& JFactory::getSession();
		$GPTIuser	=& $session->get( 'GPTI_user', null );
		
		$REQ		=& JTable::getInstance('requerimientos', 'GPTI');
		$REQ->get( JRequest::getInt('REQ_ID',0) );
		$REQ->bind( $_POST );
	
		$REQ->REQ_ESTADO	= 10 /* en prueba */ ;
		$REQ->REQ_FASE 		= 5; //fase 5
		$REQ->REQ_FECHA_MODIFICACION=  date("Y-m-d") ; 

		if( $REQ->save() ):
			$REQ->get(  $REQ->REQ_ID  );
			$msj	= base64_encode('El requerimiento ('.$REQ->REQ_DRU.') se ha enviado a prueba con éxito');
			
			/*
			$datosp = array();
			$datosg = array();
			$datosa = array();
			
			$emails = '' ; 
			$datosp['perfil'] 	= 5;
			//$datos['gerencia'] 	= $GPTIuser->USR_GERENCIA;
			$datosp['proveedor']	= $REQ->REQ_PROVEEDOR; 		
			$PROVS = GPTIHelperUsuarios::getUsuarios( $datosp );
			foreach( $PROVS as $i => $PROV ):
				 $emails .= ($i ? ',' : '' ).$PROV['joomla']->email;
			endforeach;
			
			$datogs['perfil'] 	= 3;
			$datos['gerencia'] 	= $GPTIuser->USR_GERENCIA;
			//$datosg['proveedor']	= $REQ->REQ_PROVEEDOR; 		
			$GERS = GPTIHelperUsuarios::getUsuarios( $datosg );
			
			foreach( $GERS as $i => $GER ):
				 $emails .= ( $emails != '' ? ',' : '' ).$GER['joomla']->email;
			endforeach;

			$datosa['perfil'] 	= 1;
			//$datos['gerencia'] 	= $GPTIuser->USR_GERENCIA;
			//$datosa['proveedor']	= $REQ->REQ_PROVEEDOR; 		
			$ADMS = GPTIHelperUsuarios::getUsuarios( $datosa );
			
			foreach( $ADMS as $i => $ADM ):
				 $emails .= ( $emails != '' ? ',' : '' ).$ADM['joomla']->email;
			endforeach;
			*/

			$USRS				= GPTIHelperUsuarios::getUsuarios( array('gerencia'=>$REQ->REQ_GERENCIA) );
			$emails				= '';
			foreach( $USRS as $i => $USR ):
				 $emails		.= ( $i ? ',' : '' ) . $USR['joomla']->get('email');
			endforeach;

			$USRScc				= GPTIHelperUsuarios::getUsuarios( array('perfil'=>1) );
			$emailsCC				= '';
			foreach( $USRScc as $i => $USRcc ):
				 $emailsCC		.= ( $i ? ',' : '' ) . $USRcc['joomla']->get('email');
			endforeach;

			$vars['rte'] 		= $GPTIuser->joomla->email;
			$vars['dst'] 		= $emails;
			$vars['cc'] 		= $emailsCC;
			$vars['bcc'] 		= '';
			$vars['sujeto'] 	= '[GPTI] '.$REQ->REQ_NOMBRE .' ( DRU Nro. '.$REQ->REQ_DRU.') ';
			$vars['detalle'] 	= '';
			$vars['html'] 		= 'S';
			$vars['titulo'] 	= $REQ->REQ_NOMBRE .' ( DRU Nro. '.$REQ->REQ_DRU.') ';
			$vars['introtext'] 	= 'Se ha pasado a prueba este requerimiento';
				
			$obs				= JRequest::getVar('OBSERVACION',"");
			$obs				= $obs == "" || strtoupper($obs) == "OBSERVACION" || strtoupper($obs) == "OBSERVACIÓN" ? "No se ingresaron motivos." : $obs;
			$vars['fulltext'] 	= "<b>Motivo :</b><br />$obs";

				
			$plantilla			= 'requerimiento';
			
			$tmpl				= JPATH_BASE.DS.'components'.DS.'com_gpti'.DS.'correos'.DS.$plantilla.'.php';		
			if( file_exists( $tmpl ) ) :
				include_once( $tmpl );
				$vars['detalle'] 	= _plantilla( $REQ , $vars );
				GPTIHelperCorreo::Encolar( $vars );
			endif;	

		endif;
		$mainframe->redirect( "index.php?option=com_gpti&Itemid=$Itemid&c=requerimientos&task=ver&REQ_ID=".$REQ->REQ_ID."&msj=$msj" );
	}
	
	
	
	/*function tarea_check()
	{

	global $mainframe, $Itemid;

		if(JRequest::getVar('task') == 'check_gerencia'):
			$errores  = GPTIHelperValidacion::formTareaGerente();
		elseif(JRequest::getVar('task') == 'check_ejecutor'):
			$errores  = GPTIHelperValidacion::formTareaEjecutor();		
		endif;

		if( $errores ) :
			$msj	= base64_encode("Los siguientes campos son obligatorios : <br /> ".(implode( "<br />" , $errores )) );
			$mainframe->redirect( "index.php?option=com_gpti&Itemid=$Itemid&ntareas=".JRequest::getInt('ntareas')."&task=planificar&REQ_ID=".JRequest::getInt('REQ_ID')."&msj=$msj" );
			
			return;
		else:
			$this->tarea_submit();
		endif;
		
	}*/
	
	function tarea_check()
	{
		if( $errores = GPTIHelperValidacion::formTareaGerente() ) :
			$texto	= 'El formulario presenta los siguientes errores : <br />';
			header('Content-type: application/json');
			?>
			{
					"error": "1",
					"errormsj": "<?php echo $texto . implode( '<br />' , $errores ); ?>"
			}
			<?php
			exit(0);
		endif;		
		
		header('Content-type: application/json');
		?>
		{
				"error": "0",
                "errormsj": ""
		}
		<?php
		exit(0); 		
	}	
	
	function tarea_submit()
	{
		global $mainframe, $Itemid;
		$vars[] = array();
		$datos[] = array();

		$session			=& JFactory::getSession();
		$GPTIuser			=& $session->get( 'GPTI_user', null );

		if( !GPTIHelperACL::check('req_programar') && !GPTIHelperACL::check('req_informar') ) :
			GPTIHelperError::Raise( 'Usted no tiene permiso para realizar esta operación' );
		endif;
		
		
		$idtareas		= JRequest::getVar('TAR_ID' , array() );
		$nombre			= JRequest::getVar('TAR_NOMBRE', array() );
		$hhestimada		= JRequest::getVar('TAR_HH_ESTIMADA', array() );
		$recurso		= JRequest::getVar('TAR_RECURSO', array() );
		$tipo			= JRequest::getVar('TAR_TIPO', array() );
		$f_inicio		= JRequest::getVar('TAR_FECHA_INICIO', array() );
		$f_termino		= JRequest::getVar('TAR_FECHA_TERMINO', array() );
		$observaciones	= JRequest::getVar('TAR_OBSERVACIONES', array() );

		 if(( JRequest::getVar('task')!= 'tarea_rechaza') && (JRequest::getVar('task') != 'tarea_aprueba')):
		
				if( JRequest::getVar('task') == 'check_gerencia') :
					if( GPTIHelperValidacion::formTareaGerente() ) :
						$this->planificar();
						return;
					endif;		
				
				elseif( JRequest::getVar('task') == 'check_ejecutor') :
					if( GPTIHelperValidacion::formTareaEjecutor() ) :
						$this->planificar();
						return;
					endif;		
				endif;
				
		endif;
		
		if( count($nombre) ):
			foreach( $idtareas  as $indice => $idtarea ):
				$forbind 		= array(
										'TAR_NOMBRE'		=>	$nombre[$indice] , 
										'TAR_HH_ESTIMADA'	=>	$hhestimada[$indice] , 
										'TAR_RECURSO'		=>	$recurso[$indice] , 
										'TAR_TIPO'			=>	$tipo[$indice] , 
										'TAR_FECHA_INICIO'	=>	$f_inicio[$indice] , 
										'TAR_FECHA_TERMINO'	=>	$f_termino[$indice] , 
										'TAR_OBSERVACIONES'	=>	$observaciones[$indice] 
									);
				unset( $TAR );
				$TAR			=& JTable::getInstance( 'tareas' , 'GPTI');
				$TAR->get( $idtareas[$indice] );
				$TAR->bind( $forbind );
				$TAR->TAR_REQ	= JRequest::getInt('REQ_ID') ;
				if( !$TAR->save() ){
					$msj	= base64_encode('No se pudo guardar la planificación enviada.');
					$mainframe->redirect( "index.php?option=com_gpti&Itemid=$Itemid&c=requerimientos&task=ver&REQ_ID=".JRequest::getInt('REQ_ID')."&msj=$msj" );
				}
			endforeach;
		endif;
		
			$REQ		=& JTable::getInstance('requerimientos', 'GPTI');
			$REQ->get( JRequest::getInt('REQ_ID') );
			$REQ->bind( $_POST );
			
			$REQ->REQ_FASE 			= 4; //fase 4
			if( JRequest::getVar('task') == 'check_gerencia') :
				$REQ->REQ_NRO_INTERNO 	= JRequest::getVar('REQ_NRO_INTERNO') == 'Nro. Interno' ? '' : JRequest::getVar('REQ_NRO_INTERNO'); 
			endif;
			
			if( JRequest::getVar('task') == 'tarea_rechaza' ):
				$datos['proveedor']	= $REQ->REQ_PROVEEDOR;
				$REQ->REQ_ESTADO	=   8 /* programacion rechazada */ ;
				$REQ->REQ_FECHA_MODIFICACION=  date("Y-m-d") ; 
				$vars['introtext'] 	= 'Se ha Rechazado la planificación para este requerimiento' ;
				$msj	= base64_encode('La Planificación de este requerimiento, ha sido rechazada con éxito');
			endif;
			
			if( JRequest::getVar('task') == 'tarea_aprueba' ):
				$datos['proveedor']	= $REQ->REQ_PROVEEDOR;
				$REQ->REQ_ESTADO	=   9 /* en produccion */ ;
				$REQ->REQ_FECHA_MODIFICACION=  date("Y-m-d") ; 
				$vars['introtext'] 	= 'Se ha Aceptado la planificación para este requerimiento' ;
				$msj	= base64_encode('La Planificación de este requerimiento, ha sido aceptada con éxito');
			endif;
			
			if( JRequest::getVar('task') == 'check_gerencia' ):
				$datos['perfil'] 	= 1;
				$REQ->REQ_ESTADO	=   7 /* programacion proveedor */ ;
				$REQ->REQ_FECHA_MODIFICACION=  date("Y-m-d") ; 
				$vars['introtext'] 	= 'Se ha planificado este requerimiento' ;
				$msj	= base64_encode('La Planificación de este requerimiento, ha sido ingresada con éxito');
			endif;

			if( $REQ->save() ):
				$REQ->get(  $REQ->REQ_ID  );
				
				$USRS				= GPTIHelperUsuarios::getUsuarios( $datos );
				$emails				= '';
				foreach( $USRS as $i => $USR ):
					 $emails		.= ($i ? ',' : '' ).$USR['joomla']->get('email');
				endforeach;

				$vars['rte'] 		= $GPTIuser->joomla->email ;
				$vars['dst'] 		= $emails ;
				$vars['cc'] 		= $GPTIuser->joomla->email ;
				$vars['bcc'] 		= '' ;
				$vars['sujeto'] 	= '[GPTI] '.$REQ->REQ_NOMBRE .' ( DRU Nro. '.$REQ->REQ_DRU.') ' ;
				$vars['detalle'] 	= '' ;
				$vars['html'] 		= 'S' ;
				$vars['titulo'] 	= $REQ->REQ_NOMBRE .' ( DRU Nro. '.$REQ->REQ_DRU.') ' ;
				
				$obs				= JRequest::getVar('OBSERVACION',"");
				$obs				= $obs == "" || strtoupper($obs) == "OBSERVACION" || strtoupper($obs) == "OBSERVACIÓN" ? "No se ingresaron motivos." : $obs;
				$vars['fulltext'] 	= "<b>Motivo :</b><br />$obs";

					
				$plantilla			= 'requerimiento';
				
				$tmpl				= JPATH_BASE.DS.'components'.DS.'com_gpti'.DS.'correos'.DS.$plantilla.'.php';		
				if( file_exists( $tmpl ) ) :
					include_once( $tmpl );
					$vars['detalle'] 	= _plantilla( $REQ , $vars );
					GPTIHelperCorreo::Encolar( $vars );
				endif;	
				
				$mainframe->redirect( "index.php?option=com_gpti&Itemid=$Itemid&c=requerimientos&task=ver&REQ_ID=".JRequest::getInt('REQ_ID')."&msj=$msj" );
			
			
			else :
				$msj	= base64_encode('La planificación debe tener como mínimo una tarea');
				$mainframe->redirect( "index.php?option=com_gpti&Itemid=$Itemid&c=requerimientos&task=planificar&REQ_ID=".JRequest::getInt('REQ_ID')."&msj=$msj" );
			
			endif;
		
	}
	
	function cerrar()
	{	
	
 		if( !GPTIHelperACL::check('req_cerrar') ) :
			GPTIHelperError::Raise( 'Usted no tiene permiso para realizar esta operación' );
		endif;
		$vars[] = array();
		
		global $mainframe, $Itemid;
		$session	=& JFactory::getSession();
		$GPTIuser	=& $session->get( 'GPTI_user', null );
		
		$REQ		=& JTable::getInstance('requerimientos', 'GPTI');
		$REQ->get( JRequest::getInt('REQ_ID',0) );
		$REQ->bind( $_POST );
	
		$REQ->REQ_ESTADO	= 14 /* cerrrado */ ;
		$REQ->REQ_FECHA_MODIFICACION=  date("Y-m-d") ; 
		$REQ->REQ_FASE 		= 6; //fase 6
		$REQ->REQ_PRIORIDAD = NULL; //
		
	
		if( $REQ->save() ):
			$msj	= base64_encode('El requerimiento ('.$REQ->REQ_DRU.') se ha Cerrado con éxito');

			$datosp = array();
			$datosg = array();
			$datosa = array();
			$datose = array();
			$datosu = array();
			
			/*$emails = '' ; 
			$datose['perfil'] 	= 6;
			//$datos['gerencia'] 	= $GPTIuser->USR_GERENCIA;
			$datose['proveedor']	= $REQ->REQ_PROVEEDOR; 		
			$EJES = GPTIHelperUsuarios::getUsuarios( $datose );
			foreach( $EJES as $i => $EJE ):
				 $emails .= ($i ? ',' : '' ).$EJE['joomla']->email;
			endforeach;
			
			$emails = '' ; 
			$datosu['perfil'] 	=  $REQ->REQ_USUARIO;
			$datosu['gerencia'] 	= $GPTIuser->USR_GERENCIA;
			//$datosp['proveedor']	= $REQ->REQ_PROVEEDOR; 		
			$USRS = GPTIHelperUsuarios::getUsuarios( $datosu );
			foreach( $USRS as $i => $USR ):
				 $emails .= ($i ? ',' : '' ).$USR['joomla']->email;
			endforeach;
			
			$emails = '' ; 
			$datosp['perfil'] 	= 5;
			//$datos['gerencia'] 	= $GPTIuser->USR_GERENCIA;
			$datosp['proveedor']	= $REQ->REQ_PROVEEDOR; 		
			$PROVS = GPTIHelperUsuarios::getUsuarios( $datosp );
			foreach( $PROVS as $i => $PROV ):
				 $emails .= ($i ? ',' : '' ).$PROV['joomla']->email;
			endforeach;
			
			$datosg['perfil'] 	= 3;
			$datosg['gerencia'] 	= $GPTIuser->USR_GERENCIA;
			//$datosg['proveedor']	= $REQ->REQ_PROVEEDOR; 		
			$GERS = GPTIHelperUsuarios::getUsuarios( $datosg );
			
			foreach( $GERS as $i => $GER ):
				 $emails .= ( $emails != '' ? ',' : '' ).$GER['joomla']->email;
			endforeach;

			$datosa['perfil'] 	= 1;
			//$datos['gerencia'] 	= $GPTIuser->USR_GERENCIA;
			//$datosa['proveedor']	= $REQ->REQ_PROVEEDOR; 		
			$ADMS = GPTIHelperUsuarios::getUsuarios( $datosa );
			
			foreach( $ADMS as $i => $ADM ):
				 $emails .= ( $emails != '' ? ',' : '' ).$ADM['joomla']->email;
			endforeach;
			*/
			
			$emails				= '';
			$USRS				= GPTIHelperUsuarios::getUsuarios( array('perfil'=>5,'proveedor'=>$REQ->REQ_PROVEEDOR) );
			foreach( $USRS as $i => $USR ):
				 $emails		.= ( $i ? ',' : '' ) . $USR['joomla']->get('email');
			endforeach;
			
			$emailsCC			= '';
			$USRScc				= GPTIHelperUsuarios::getUsuarios( array('perfil'=>1) );
			foreach( $USRScc as $i => $USRcc ):
				 $emailsCC		.= ( $emailsCC != '' ? ',' : '' ) . $USRcc['joomla']->get('email');
			endforeach;

			$USRScc				= GPTIHelperUsuarios::getUsuarios( array('gerencia'=>$REQ->REQ_GERENCIA) );
			foreach( $USRScc as $i => $USRcc ):
				 $emailsCC		.= ( $emailsCC != '' ? ',' : '' ) . $USRcc['joomla']->get('email');
			endforeach;
			
			$vars['rte'] 		= $GPTIuser->joomla->email ;
			$vars['dst'] 		= $emails ;
			$vars['cc'] 		= $emailsCC ;
			$vars['bcc'] 		= '' ;
			$vars['sujeto'] 	= '[GPTI] '.$REQ->REQ_NOMBRE .' ( DRU Nro. '.$REQ->REQ_DRU.') ' ;
			$vars['detalle'] 	= '' ;
			$vars['html'] 		= 'S' ;
			$vars['titulo'] 	= $REQ->REQ_NOMBRE .' ( DRU Nro. '.$REQ->REQ_DRU.') ' ;
			$vars['introtext'] 	= 'Se ha Cerrado este requerimiento' ;
			$vars['fulltext'] 	= '' ;
				
			$plantilla			= 'requerimiento';
			
			$tmpl				= JPATH_BASE.DS.'components'.DS.'com_gpti'.DS.'correos'.DS.$plantilla.'.php';		
			if( file_exists( $tmpl ) ) :
				include_once( $tmpl );
				$vars['detalle'] 	= _plantilla( $REQ , $vars );
				GPTIHelperCorreo::Encolar( $vars );
			endif;	


		endif;
		$mainframe->redirect( "index.php?option=com_gpti&Itemid=$Itemid&task=ver&REQ_ID=".$REQ->REQ_ID."&msj=$msj" );
	}
}