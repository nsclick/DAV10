<?php
/**
 * @version		$Id: helper.validacion.php 2011-05-20 Sebastián García Truan $
 * @package		Joomla
 * @subpackage	GPTI
 * @autor		Diseño Objetivo www.do.cl
 * @copyright	Copyright (C) 2006 - 2011 Diseño Objetivo. Todos los derechos reservados.
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
defined( '_JEXEC' ) or die( 'El acceso directo a este archivo no está permitido.' );
defined( '_DO_GPTI' ) or die( 'El acceso directo a este archivo no está permitido.' );

class GPTIHelperValidacion
{
	function formIngreso()
	{
		
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );

		$post		= $_POST;
		$errores	= array();
		
		$reqFecha		= "/^\d{4}-\d{1,2}-\d{1,2}$/";
	
		/*if( !$post['REQ_PROYECTO'] ) :
			$errores[]	= '- &quot;Proyecto&quot;';
		endif;*/
		
		if( !$post['REQ_NOMBRE'] || $post['REQ_NOMBRE'] == 'Nombre Corto del Requerimiento' ) :
			$errores[]	= '- &quot;Nombre Corto del Requerimiento&quot; es un campo obligatorio';
		endif;
		
		if( GPTIHelperACL::check('req_ingresar_ext_2') ):
			if( !$post['REQ_TIPO'] ) :
				$errores[]	= '- &quot;Tipo&quot;';
			endif;
			if( !$post['REQ_GERENCIA'] ) :
				$errores[]	= '- &quot;Gerencia&quot;';
			endif;
		endif;
				
		if( !$post['REQ_FECHA_ENTREGA'] || $post['REQ_FECHA_ENTREGA'] == 'Fecha de Entrega Esperada' ) :
			$errores[]	= '- &quot;Fecha de Entrega Esperada&quot; es un campo obligatorio';
		elseif( !preg_match($reqFecha, $post['REQ_FECHA_ENTREGA'])/* || strtotime($post['REQ_FECHA_ENTREGA']." 23:59") < time()*/ ) :
			$errores[]	= '- &quot;Fecha de Entrega Esperada&quot; es una fecha inválida';
		endif;
		
		if( !$post['REQ_PROPOSITO'] || $post['REQ_PROPOSITO'] == 'Propósitos' ) :
			$errores[]	= '- &quot;Propósitos&quot; es un campo obligatorio';
		endif;
		
		if( !$post['REQ_DIAGNOSTICO'] || $post['REQ_DIAGNOSTICO'] == 'Diagnóstico' ) :
			$errores[]	= '- &quot;Diagnóstico&quot; es un campo obligatorio';
		endif;
		
		if( !$post['REQ_CAPACIDADES'] || $post['REQ_CAPACIDADES'] == 'Capacidades' ) :
			$errores[]	= '- &quot;Capacidades&quot; es un campo obligatorio';
		endif;

		$mods	= JRequest::getVar('REQ_MODULOS',array(),'request','array');
		if( !isset($mods) || !$mods || !is_array($mods) || !count($mods) || $mods[0]=="null" ) :
			$errores[]	= '- &quot;Módulos que Afectan&quot; es un campo obligatorio';
		endif;
		
		if( GPTIHelperACL::check('req_ingresar_ext_1') || GPTIHelperACL::check('req_ingresar_ext_2') ) :
			$areas	= JRequest::getVar('REQ_AREAS',array(),'request','array');
			if( !isset($areas) || !$areas || !is_array($areas) || !count($areas) || $areas[0]=="null" ) :
				$errores[]	= '- &quot;Areas de Desarrollo de Soluciones&quot; es un campo obligatorio';
			endif;
	
			$valores= JRequest::getVar('REQ_VALORES',array(),'request','array');
			if( !isset($valores) || !$valores || !is_array($valores) || !count($valores) || $valores[0]=="null" ) :
				$errores[]	= '- &quot;Dimensiones de Valor de Soluciones&quot; es un campo obligatorio';
			endif;
		endif;

		if( count($errores) ){
			return $errores;
		}
		return false;
	}
	
	function formTareaGerente()
	{
		$post		= $_POST;
		$errores	= array();
		$indice 	= 0 ;
		
		$reqFecha		= "/^\d{4}-\d{1,2}-\d{1,2}$/";
		
		for( $i=0,$o=1 ; $i != $post['ntareas'] ; $i++,$o++ )
		{
			unset($errorestareas);
			$errorestareas			= array();
			
			if( !$post['TAR_NOMBRE'][$i] || $post['TAR_NOMBRE'][$i] == 'Nombre de la Tarea' ) :
				$errorestareas[]	= '- &quot;Nombre de la Tarea&quot; es un campo obligatorio';
			endif;
					
			if( !$post['TAR_HH_ESTIMADA'][$i] || $post['TAR_HH_ESTIMADA'][$i] == 'Estimación HH' ) :
				$errorestareas[]	= '- &quot;Estimación HH&quot; es un campo obligatorio';
			elseif( !is_numeric($post['TAR_HH_ESTIMADA'][$i]) ) :
				$errorestareas[]	= '- &quot;Estimación HH&quot; campo inválido';
			endif;
					
			if( !$post['TAR_RECURSO'][$i] ) :
				$errorestareas[]	= '- &quot;Ejecutor&quot; es un campo obligatorio';
			endif;
					
			if( !$post['TAR_TIPO'][$i] ) :
				$errorestareas[]	= '- &quot;Tipo&quot; es un campo obligatorio';
			endif;
					
			if( !$post['TAR_FECHA_INICIO'][$i] || $post['TAR_FECHA_INICIO'][$i] == 'Fecha Inicio' ) :
				$errorestareas[]	= '- &quot;Fecha Inicio&quot; es un campo obligatorio';
			elseif( !preg_match($reqFecha, $post['TAR_FECHA_INICIO'][$i]) || strtotime($post['TAR_FECHA_INICIO'][$i]." 23:59") < time() ) :
				$errorestareas[]	= '- &quot;Fecha Inicio&quot; es una fecha inválida';
			endif;
					
			if( !$post['TAR_FECHA_TERMINO'][$i] || $post['TAR_FECHA_TERMINO'][$i] == 'Fecha Termino' ) :
				$errorestareas[]	= '- &quot;Fecha Termino&quot; es un campo obligatorio';
			elseif( !preg_match($reqFecha, $post['TAR_FECHA_TERMINO'][$i]) || strtotime($post['TAR_FECHA_TERMINO'][$i]." 23:59") < time() ) :
				$errorestareas[]	= '- &quot;Fecha Termino&quot; es una fecha inválida';
			endif;
			
			if( preg_match($reqFecha, $post['TAR_FECHA_INICIO'][$i]) && preg_match($reqFecha, $post['TAR_FECHA_TERMINO'][$i]) && strtotime($post['TAR_FECHA_INICIO'][$i]) > strtotime($post['TAR_FECHA_TERMINO'][$i]) ) :
				$errorestareas[]	= '- &quot;Rango Fecha&quot; es inválido';
			endif;
		
			if( count( $errorestareas ) ) :
				$errores[]	= "- Tarea N&deg;".$o.": ";
				foreach( $errorestareas as $errortarea ) :
					$errores[]	= "&nbsp;&nbsp;&nbsp;$errortarea";
				endforeach;
			endif;
		}

		if( count($errores) ){
			return $errores;
		}

		return false;
	}	
	
	function formTareaEjecutor()
	{
		$post		= $_POST;
		$errores	= array();
		
		$reqFecha		= "/^\d{4}-\d{1,2}-\d{1,2}$/";
	
		if( !$post['TAR_HH_INFORMADA'] || $post['TAR_HH_INFORMADA'] == 'HH Informadas' ) :
			$errores[]	= '- &quot;HH Informadas&quot; es un campo obligatorio';
		elseif( !is_numeric($post['TAR_HH_INFORMADA']) ) :
			$errores[]	= '- &quot;HH Informadas&quot; campo inválido';
		endif;
				
		if( !$post['TAR_FECHA_INICIO_REAL'] || $post['TAR_FECHA_INICIO_REAL'] == 'Fecha Inicio Real' ) :
			$errores[]	= '- &quot;Fecha Inicio Real&quot; es un campo obligatorio';
		elseif( !preg_match($reqFecha, $post['TAR_FECHA_INICIO_REAL'])/* || strtotime($post['REQ_FECHA_ENTREGA']) < time()*/ ) :
			$errores[]	= '- &quot;Fecha Inicio Real&quot; es una fecha inválida';
		endif;
				
		if( !$post['TAR_FECHA_TERMINO_REAL'] || $post['TAR_FECHA_TERMINO_REAL'] == 'Fecha Termino Real' ) :
			$errores[]	= '- &quot;Fecha Termino Real&quot; es un campo obligatorio';
		elseif( !preg_match($reqFecha, $post['TAR_FECHA_TERMINO_REAL'])/* || strtotime($post['REQ_FECHA_ENTREGA']) < time()*/ ) :
			$errores[]	= '- &quot;Fecha Termino Real&quot; es una fecha inválida';
		endif;
		
		if( preg_match($reqFecha, $post['TAR_FECHA_INICIO_REAL']) && preg_match($reqFecha, $post['TAR_FECHA_TERMINO_REAL']) && strtotime($post['TAR_FECHA_INICIO_REAL']) > strtotime($post['TAR_FECHA_TERMINO_REAL']) ) :
			$errores[]	= '- &quot;Rango Fecha Real&quot; es inválido';
		endif;
		
		if( count($errores) ){
			return $errores;
		}
		return false;
	}

}
?>