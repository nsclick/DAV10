<?php
/**
 * @version		$Id: inicio.php 2011-05-26 Max Roa Barba
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
class GPTIControllerReportes extends JController  
{
	/**
	 * Constructor*
	 */
	function __construct()
	{
		parent::__construct( array() );
		
		$this->registerTask( 'espera',		'display' );
		$this->registerTask( 'desarrollo',		'display' );
	}

	function display() 
	{
		//ob_start();
		global $mainframe, $Itemid;
		$doc		=& JFactory::getDocument();
		

		$REQ		=& JTable::getInstance('requerimientos', 'GPTI');

		$lists								= array();

		$REQ->REQ_FECHA_DESDE				= $mainframe->getUserStateFromRequest( $context.'filtro_fecha_desde',	'filtro_fecha_desde',		'',			'string' 	);
		$REQ->REQ_FECHA_HASTA				= $mainframe->getUserStateFromRequest( $context.'filtro_fecha_hasta',	'filtro_fecha_hasta',		'',			'string' 	);
		
		//$lists['calendario-fecha-desde']	= GPTIHelperHtml::Calendario( 'filtro_fecha_desde', 'Ingreso Desde', $REQ->REQ_FECHA_DESDE, NULL, '%Y-%m-%d', 'class="inputclass ancho_x"' );
		//$lists['calendario-fecha-hasta']	= GPTIHelperHtml::Calendario( 'filtro_fecha_hasta', 'Ingreso Hasta', $REQ->REQ_FECHA_HASTA, NULL , '%Y-%m-%d', 'class="inputclass ancho_x"' );
		$lists['reporte-tarea']				= JRequest::getVar('task') ;
		
		if( $lists['reporte-tarea'] == 'desarrollo' ) :
			$reporte		= $REQ->getReporteDesarrollo();
		elseif( $lists['reporte-tarea'] == 'espera' ) :
			$reporte		= $REQ->getReporteEspera();
		endif;
		
		//echo "<pre>"; print_r($reporte); echo "</pre>"; exit;
		
		require_once(JPATH_COMPONENT.DS.'vistas'.DS.'reportes.php');
		//ob_end_clean();
		GPTIVistaReportes::display( $reporte, $lists );
		
	}

}