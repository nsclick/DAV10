<?php
/**
 * @version		$Id: do.php 2010-07-21 sgarcia $
 * @package		Joomla
 * @subpackage	DO
 * @copyright	Copyright (C) 2006 - 2010 Diseño Objetivo. Todos los derechos reservados.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

$mainframe->registerEvent( 'onPrepareContent', 'onPrepararDO' );

	/**
	 * Example prepare content method
	 *
	 * Method is called by the view
	 *
	 * @param 	object		The row object.  Note $article->text is also available
	 * @param 	object		The object params
	 * @param 	int			The 'page' number
	 */
	function onPrepararDO( &$row, &$params, $page )
	{
		global $mainframe;

		$plugin			=& JPluginHelper::getPlugin('content', 'do');
		$pluginParams	= new JParameter( $plugin->params );

		// check whether plugin has been unpublished
		if ( !$pluginParams->get( 'enabled', 1 ) ) :
			$row->text = preg_replace( $regex, '', $row->text );
			return true;
		endif;
		
		$regex = '/<p>{do (.*)}<\/p>/iU';
		// find all instances of plugin and put in $matches
		preg_match_all( $regex, $row->text, $matches );
		// Number of plugins
		$count = count( $matches[0] );
		
		if( !$count ) :
			$regex = '/{do (.*)}/iU';
			// find all instances of plugin and put in $matches
			preg_match_all( $regex, $row->text, $matches );
			// Number of plugins
			$count = count( $matches[0] );
		endif;
		
		// plugin only processes if there are any instances of the plugin in the text
		if ( $count ) :
			
			for ( $i=0; $i < $count; $i++ ) :
				//$video = renderVideo($matches[1][$i], $pluginParams);
				$argumentos		= explode( "|", $matches[1][$i] );
				$vars			= array();
				$html			= "";
				if( count( $argumentos ) ) :
					foreach( $argumentos as $argumento ) :
						list( $nombre, $valor )	= explode( ":", $argumento );
						$vars[ $nombre ]		= $valor;
					endforeach;
				endif;
				switch( $vars['t'] ) :
					case "formulario"	:	$html		= formulario( $vars, $row );										break;
					//case "imagen"		:	$html		= imagenDicoex( $vars, $row->title );								break;
					//case "video"		:	$html		= videoDicoex( $vars, $row );										break;
				endswitch;
				
				$row->text 	= preg_replace( $regex, $html, $row->text, 1);
			endfor;
		endif;
		
		return '';
	}
	
	function formulario( $vars=array(), $row=null )
	{
		$msg			= JRequest::getVar('msg','','request','string');
		$javascript		= "";
		
		$vars['email']	= str_replace( "[at]", "@", $vars['email'] );
		
		/*
		// ajax
		$ajax		= JPATH_BASE.DS.'plugins'.DS.'content'.DS.'dicoex'.DS.'captcha'.DS.'ajax.js.php';
		if( file_exists( $ajax ) ) :
			$javascript	= '<script type="text/javascript">';
			ob_start();
			require_once( $ajax );
			$javascript	.= ob_get_contents();
			ob_end_clean();
			$javascript	.= '</script>';
		endif;
		*/
		
		if( !file_exists( dirname(__FILE__).DS.'do'.DS.'tmpl_form_'.$vars['form'].'.php' ) ) :
			return '';
		endif;
				
		ob_start();
			include_once( dirname(__FILE__).DS.'do'.DS.'tmpl_form_'.$vars['form'].'.php' );
			$html = ob_get_contents();
		ob_end_clean();
		return $javascript.$html;	
	}
	
	function imagenDicoex( $vars=array(), $title='' )
	{
		$img			= $vars['img'];
		$_ancho			= isset( $vars['ancho'] ) ? $vars['ancho'] : 0;
		
		$base			= dirname(__FILE__);
		$path			= JPATH_BASE.DS.$img;
		$title			= str_replace( '"', "'", $title );
		//$urlimagen = $_GET["c"];
		
		if( !file_exists( $path ) ) :
			return '';
		endif;
		
		$dimensiones	= @getimagesize( $path );
		
		switch( $_ancho ) :
			case 1	:
				$ancho	= round((int)$dimensiones[0]/2);
				$alto	= round((int)$dimensiones[1]/2);
			break;
			case 0	:
			default	:
				$ancho	= (int)$dimensiones[0];
				$alto	= (int)$dimensiones[1];
			break;
		endswitch;
		
		/*
		$esq_sup_izq 	= imagecreatefrompng($base.DS.'dicoex'.DS.'imagen'.DS.'esquina_sup_izq.png');
		$esq_inf_izq 	= imagecreatefrompng($base.DS.'dicoex'.DS.'imagen'.DS.'esquina_inf_izq.png');
		$esq_sup_der 	= imagecreatefrompng($base.DS.'dicoex'.DS.'imagen'.DS.'esquina_sup_der.png');
		$esq_inf_der 	= imagecreatefrompng($base.DS.'dicoex'.DS.'imagen'.DS.'esquina_inf_der.png');
		$img			= imagecreatefromjpeg($path);
		
		imagecopy($img, $esq_sup_izq, 0, 0, 0, 0, imagesx($esq_sup_izq), imagesy($esq_sup_izq));
		imagecopy($img, $esq_inf_izq, 0, imagesy($img) - imagesy($esq_inf_izq), 0, 0, imagesx($esq_inf_izq), imagesy($esq_inf_izq));
		imagecopy($img, $esq_sup_der, imagesx($img) - imagesx($esq_sup_der), 0, 0, 0, imagesx($esq_sup_der), imagesy($esq_sup_der));
		imagecopy($img, $esq_inf_der, imagesx($img) - imagesx($esq_inf_der), imagesy($img) - imagesy($esq_inf_der), 0, 0, imagesx($esq_inf_der), imagesy($esq_inf_der));
		
		ob_start();
		//header('Content-type: image/jpg');
		imagepng($img);
		$imghtml = ob_get_contents();
		ob_end_clean();
		
		$encoded = chunk_split(base64_encode($imghtml)); 
		$html	= '<img src="data:image/png;base64,'.$encoded.'" width="'.$ancho.'" height="'.$alto.'" border="0" alt="'.$title.'" title="'.$title.'" />';
		*/
		
		$html	= '<img src="'.JURI::base().'imagen.php?img='.base64_encode($img).'" width="'.$ancho.'" height="'.$alto.'" border="0" alt="'.$title.'" title="'.$title.'" />';
		return $html;
	}
	
	function videoDicoex( $vars=array(), $row=null )
	{
		//{dicoex t:video|video:himno/himno_nacional_chile.flv|ancho:320|alto:240}
		$video		= $vars['video'];
		$ancho		= $vars['ancho'];
		$alto		= $vars['alto'];
		$urlbase	= JURI::base()."images/videos";
		$plantilla	= ereg(".flv",$video) ? 'flv' : 'frame';
		
		ob_start();
		include_once( dirname(__FILE__).DS.'dicoex'.DS.'tmpl_video_'.$plantilla.'.php' );
		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
	
	/*function afeepEquipo( $idequipo=0 )
	{
		$afeep	= new Afeep();
		$equipo	= $afeep->getEquipo( $idequipo );
		
		ob_start();
		include_once( dirname(__FILE__).DS.'afeep'.DS.'tmpl_equipo.php' );
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
	
	function afeepTabla( $serie='J' )
	{
		$afeep	= new Afeep();
		$tabla	= $afeep->getTabla( $serie );
		
		ob_start();
		include_once( dirname(__FILE__).DS.'afeep'.DS.'tmpl_tabla.php' );
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
	
	function afeepSuspendidos()
	{
		$afeep			= new Afeep();
		$suspendidos	= $afeep->getSuspendidos();
		
		ob_start();
		include_once( dirname(__FILE__).DS.'afeep'.DS.'tmpl_suspendidos.php' );
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
	
	function afeepResultados( $idcampeonato=0, $idfecha=0 )
	{
		$afeep			= new Afeep();
		$resultados		= $afeep->getResultados( $idcampeonato, $idfecha );
		
		ob_start();
		include_once( dirname(__FILE__).DS.'afeep'.DS.'tmpl_resultados.php' );
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
	
	function afeepPapeleta( $id=0, $tmpl='' )
	{
		$afeep			= new Afeep();
		$partido		= $afeep->getPartido( $id );
		
		ob_start();
		include_once( dirname(__FILE__).DS.'afeep'.DS.'tmpl_papeleta'.$tmpl.'.php' );
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}*/