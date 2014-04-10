<?php
/**
 * @version		$Id: inicio.php 2011-05-26 Sebastián García Truan
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
class GPTIControllerInicio extends JController  
{
	/**
	 * Constructor*
	 */
	function __construct()
	{
		parent::__construct( array() );
		
		//$this->registerTask( 'correo',		'display' );
	}

	function display() 
	{
		ob_start();
		global $mainframe, $Itemid;
			
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		$db				= JFactory::getDBO();
		$lists							= array();
		
		$lists['perfil-Admin']				= $GPTIuser->USR_PERFIL == 1;
		$lists['perfil-Gerente']			= $GPTIuser->USR_PERFIL == 2;
		$lists['perfil-Gerencia']			= $GPTIuser->USR_PERFIL == 3;
		$lists['perfil-Usuario']			= $GPTIuser->USR_PERFIL == 4;
		$lists['perfil-Gerente-Proveedor']	= $GPTIuser->USR_PERFIL == 5;
		$lists['perfil-Ejecutor']			= $GPTIuser->USR_PERFIL == 6;
		
		$query			= "SELECT mm.id"
						. " FROM #__components AS com"
						. "   LEFT JOIN #__menu AS mm ON mm.componentid = com.id"
						. " WHERE com.enabled = 1"
						. "   AND com.option = 'com_gpti'"
						. "   AND com.parent = 0"
						. "   AND mm.published = 1"
						. "   AND mm.parent = 0"
						. "   AND mm.params LIKE '%controlador=requerimientos%'"
						. "   AND mm.params LIKE '%tarea=ingresar%'"
						. " ORDER BY com.ordering ASC, mm.ordering ASC"
						;
		$db->setQuery($query);
		$lists['menu-ingresar']			= (int)$db->loadResult();	
			
		$lists['text-numero-int']		= GPTIHelperHtml::Text('filtro_n_interno','Nº Interno',null,'size="17" id="filtro_n_interno"');
		$lists['text-numero-dru']		= GPTIHelperHtml::Text('filtro_n_dru','Nº DRU',null,'size="13" id="filtro_n_dru"');
		$lists['select-prioridad']		= GPTIHelperHtml::SelectReqsPrioridades('filtro_prioridad');
		$lists['select-gerencia']		= GPTIHelperHtml::SelectReqsGerencia('filtro_gerencia' );
		$lists['select-proyecto']		= GPTIHelperHtml::SelectReqsProyectos('filtro_proyecto');
		$lists['select-estado']			= GPTIHelperHtml::SelectReqsEstado('filtro_estado');
		$lists['lista-derecha']			= '';
		
		if( $lists['perfil-Admin'] ) :
			$lists['lista-derecha']		= GPTIHelperHtml::ListaUltimos( array('REQ_ESTADO'=>4,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC'), 3 );
			$lists['label-derecha']		= 'Últimos Requerimientos Ingresados';
		endif;
		if( $lists['perfil-Gerente'] ) :
			$lists['lista-derecha']		= GPTIHelperHtml::ListaUltimos( array('REQ_FASE_DESDE'=>1,'REQ_FASE_HASTA'=>3), 3 );
			$lists['label-derecha']		= 'Últimos Requerimientos Ingresados';
		endif;
		if( $lists['perfil-Gerencia'] ) :
			$lists['lista-derecha']		= GPTIHelperHtml::ListaUltimos( array('REQ_GERENCIA'=>$GPTIuser->USR_GERENCIA,'REQ_ESTADO'=>2,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC'), 3 );
			$lists['label-derecha']		= 'Últimos Requerimientos Ingresados';
		endif;
		if( $lists['perfil-Usuario'] ) :
			$lists['lista-derecha']		= GPTIHelperHtml::ListaUltimos( array('REQ_USUARIO'=>$GPTIuser->USR_ID,'REQ_ESTADO'=>2,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC'), 3 );
			$lists['label-derecha']		= 'Últimos Requerimientos Ingresados';
		endif;
		if( $lists['perfil-Gerente-Proveedor'] ) :
			$lists['lista-izquierda']	= GPTIHelperHtml::ListaUltimos( array('REQ_PROVEEDOR'=>$GPTIuser->USR_PROVEEDOR,'REQ_ESTADO'=>6,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC'), 3 );
			$lists['label-izquierda']	= 'Últimos Requerimientos para HH Estimadas';
			$lists['lista-derecha']		= GPTIHelperHtml::ListaUltimos( array('REQ_PROVEEDOR'=>$GPTIuser->USR_PROVEEDOR,'REQ_ESTADO'=>13,'orden'=>'ORDER BY REQS.REQ_FECHA_MODIFICACION DESC'), 3 );
			$lists['label-derecha']		= 'Últimos Requerimientos Informados';
		endif;
		if( $lists['perfil-Ejecutor'] ) :
			$lists['lista-izquierda']	= GPTIHelperHtml::ListaUltimos( array('REQ_PROVEEDOR'=>$GPTIuser->USR_PROVEEDOR,'REQ_ESTADO'=>9), 3 );
			$lists['label-izquierda']	= 'Últimos Requerimientos en Cola';
			$lists['lista-derecha']		= GPTIHelperHtml::ListaUltimos( array('REQ_PROVEEDOR'=>$GPTIuser->USR_PROVEEDOR,'REQ_ESTADO'=>12), 3 );
			$lists['label-derecha']		= 'Últimos Requerimientos por Informar';
		endif;
		
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'inicio.php');
		ob_end_clean();
		GPTIVistaInicio::display( $lists );
	}
}