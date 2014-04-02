<?php
/**
 * @version		$Id: helper.html.php 2011-05-20 Sebastián García Truan $
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

class GPTIHelperHtml
{
	function Link( $title='', $url='', $class='', $attribs=NULL )
	{
		return	'<div class="gpti_boton_g"><a href="'.$url.'" class="'.$class.'" title="'.$title.'" '.$attribs.' >'.$title.'</a></div>';
	}
	
	function Hidden( $nombre='' , $valor=null )
	{
		$valor	= !$valor ? $titulo : $valor;
		return	'<input type="hidden" name="'.$nombre.'" value="'.$valor.'" />';
	}
	
	function Text( $nombre='', $titulo='', $valor=null, $attribs=null )
	{
		$valor	= !$valor ? $titulo : $valor;
		return	'<input type="text" name="'.$nombre.'" value="'.$valor.'" title="'.$titulo.'" onblur="javascript:form_texto_blur(this);" onfocus="javascript:form_texto_focus(this);" '.$attribs.' />';
	}
	
	function radio( $nombre='', $titulo='', $valor=null, $id=null, $attribs=null )
	{
		//$valor	= !$valor ? $titulo : $valor;
		$attribs = $attribs ? ' '.$attribs : '';
		return	'<input type="radio" name="'.$nombre.'" id="'.$id.'" value="'.$valor.'" title="'.$titulo.'"'.$attribs.' /><label for="'.$id.'">'.$titulo.'</label>';
	}
	
	function TextArea( $nombre='', $titulo='', $valor=null, $attribs=null, $rows=10, $cols=30 )
	{
		$valor	= !$valor ? $titulo : $valor;
		return	'<textarea name="'.$nombre.'" rows="'.$rows.'" cols="'.$cols.'" title="'.$titulo.'" onblur="javascript:form_texto_blur(this);" onfocus="javascript:form_texto_focus(this);">'.$valor.'</textarea>';
	}
	
	function Calendario( $nombre='', $titulo='', $valor=null, $id=null, $formato = '%Y-%m-%d', $attribs=null )
	{
		JHTML::_('behavior.calendar'); //load the calendar behavior

		if (is_array($attribs)) {
			$attribs = JArrayHelper::toString( $attribs );
		}
		
		$valor			= !$valor ? $titulo : date("Y-m-d", strtotime($valor));
		$id				= !$id ? $nombre : $id;
		
		$document =& JFactory::getDocument();
		$document->addScriptDeclaration('window.addEvent(\'domready\', function() {Calendar.setup({
        inputField     :    "'.$id.'",     // id of the input field
        ifFormat       :    "'.$formato.'",      // format of the input field
        button         :    "'.$id.'_img",  // trigger for the calendar (button ID)
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true
    	});});');

		return '<input type="text" name="'.$nombre.'" id="'.$id.'" title="'.$titulo.'" onblur="javascript:form_texto_blur(this);" onfocus="javascript:form_texto_focus(this);" value="'.htmlspecialchars($valor, ENT_COMPAT, 'UTF-8').'" '.$attribs.' />'.
				 '<img class="calendar" src="'.GPTI_TEMPLATE_URL.'imagenes/calendar.gif" alt="calendar" id="'.$id.'_img" />';
	}
	
	function SelectReqsProyectos( $nombre='', $valor=null, $attribs=null )
	{
		$session			=& JFactory::getSession();
		$GPTIconn			=& $session->get( 'GPTI_conn', null );
		$GPTIuser			=& $session->get( 'GPTI_user', null );
		
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_PROYECTOS_LISTA ( :C_CURSOR ); COMMIT; END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$c_reqs				= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$c_reqs, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		// option( $value, $text='', $value_name='value', $text_name='text', $disable=false )
		$reqs				= array( JHTML::_('select.option','','- Proyecto -') );
		
		if( @!oci_execute( $c_reqs ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $row = oci_fetch_assoc( $c_reqs ) ) :
				/*if(( $row['REQ_GERENCIA'] == $GPTIuser->USR_GERENCIA )||( !$GPTIuser->USR_GERENCIA ))
				{*/
					$reqs[]	= JHTML::_('select.option',$row['PYT_ID'],$row['PYT_NOMBRE']);
				//}
			endwhile;
			oci_free_statement( $c_reqs );
		endif;
		
		// genericlist( $arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = NULL, $idtag = false, $translate = false )
		return JHTML::_('select.genericlist', $reqs, $nombre, $attribs, 'value', 'text', $valor );
	}
		
	function SelectReqsTipos( $nombre='', $valor=null, $attribs=null )
	{
		$session		=& JFactory::getSession();
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN"
							." PKG_GPTI.PROC_REQS_TIPOS_LISTA"
							." ( :C_CURSOR );"
							." COMMIT; END;"
							;
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$c_tipos			= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$c_tipos, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		// option( $value, $text='', $value_name='value', $text_name='text', $disable=false )
		$tipos				= array( JHTML::_('select.option','','- Tipo -') );
		
		if( @!oci_execute( $c_tipos ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $row = oci_fetch_assoc( $c_tipos ) ) :
				$tipos[]	= JHTML::_('select.option',$row['RTP_ID'],$row['RTP_NOMBRE']);
			endwhile;
			oci_free_statement( $c_tipos );
		endif;
		
		// genericlist( $arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = NULL, $idtag = false, $translate = false )
		return JHTML::_('select.genericlist', $tipos, $nombre, $attribs, 'value', 'text', $valor );
	}
	
	function SelectReqsClasificacion( $nombre='', $valor=null, $attribs=null )
	{
		$session		=& JFactory::getSession();
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN"
							." PKG_GPTI.PROC_REQS_CLASIFICS_LISTA"
							." ( :C_CURSOR );"
							." COMMIT; END;"
							;
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$c_clasificaciones	= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$c_clasificaciones, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		// option( $value, $text='', $value_name='value', $text_name='text', $disable=false )
		$clasificaciones	= array( JHTML::_('select.option','','- Clasificación -') );
		
		if( @!oci_execute( $c_clasificaciones ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $row = oci_fetch_assoc( $c_clasificaciones ) ) :
				$clasificaciones[]	= JHTML::_('select.option',$row['RCL_ID'],$row['RCL_NOMBRE']);
			endwhile;
			oci_free_statement( $c_clasificaciones );
		endif;
		
		// genericlist( $arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = NULL, $idtag = false, $translate = false )
		return JHTML::_('select.genericlist', $clasificaciones, $nombre, $attribs, 'value', 'text', $valor );
	}
	
	function SelectReqsEstado( $nombre='', $valor=null, $attribs=null )
	{
		$session		=& JFactory::getSession();
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
							
		$storeprocedure		= "BEGIN"
							." PKG_GPTI.PROC_REQS_ESTADOS_LISTA"
							." ( :C_CURSOR );"
							." COMMIT; END;"
							;
							
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$c_estados	= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$c_estados, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		// option( $value, $text='', $value_name='value', $text_name='text', $disable=false )
		$estados	= array( JHTML::_('select.option','','- Estado -') );
		
		if( @!oci_execute( $c_estados ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $row = oci_fetch_assoc( $c_estados ) ) :
				$estados[]	= JHTML::_('select.option',$row['RES_ID'],$row['RES_NOMBRE']);
			endwhile;
			oci_free_statement( $c_estados );
		endif;
		
		// genericlist( $arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = NULL, $idtag = false, $translate = false )
		return JHTML::_('select.genericlist', $estados, $nombre, $attribs, 'value', 'text', $valor );
	}
	
	function SelectReqsGerencia( $nombre='', $valor=null, $attribs=null )
	{
		$session		=& JFactory::getSession();
		$GPTIconn		=& $session->get( 'GPTI_conn', null );

		$storeprocedure		= "BEGIN"
							." PKG_GPTI.PROC_REQS_GERENCIAS_LISTA"
							." ( :C_CURSOR );"
							." COMMIT; END;"
							;
							
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$c_gerencia	= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$c_gerencia, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		// option( $value, $text='', $value_name='value', $text_name='text', $disable=false )
		$gerencia	= array( JHTML::_('select.option','','- Gerencias -') );
		
		if( @!oci_execute( $c_gerencia ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $row = oci_fetch_assoc( $c_gerencia ) ) :
				$gerencia[]	= JHTML::_('select.option',$row['GER_ID'],$row['GER_NOMBRE']);
			endwhile;
			oci_free_statement( $c_gerencia );
		endif;
		
		// genericlist( $arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = NULL, $idtag = false, $translate = false )
		return JHTML::_('select.genericlist', $gerencia, $nombre, $attribs, 'value', 'text', $valor );
	}
	
	function SelectReqsPrioridades( $nombre='', $valor=null, $attribs=null )
	{
		$session		=& JFactory::getSession();
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		
		$storeprocedure		= "BEGIN"
							." PKG_GPTI.PROC_REQS_PRIORIDADES_LISTA"
							." ( :P_GERENCIA, :C_CURSOR );"
							." COMMIT; END;"
							;
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$p_gerencia		= (int)$GPTIuser->gerencia;
		oci_bind_by_name( $stmt, ':P_GERENCIA', 			$p_gerencia,		40 );
		
		$c_prioridades	= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$c_prioridades, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		// option( $value, $text='', $value_name='value', $text_name='text', $disable=false )
		$prioridades		= array();
		
		if( @!oci_execute( $c_prioridades ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $row = oci_fetch_assoc( $c_prioridades ) ) :
				$prioridades[]	= JHTML::_('select.option',$row['REQ_ID'],'Antes que &quot;'.$row['REQ_NOMBRE'].'&quot;');
			endwhile;
			oci_free_statement( $c_prioridades );
		endif;
		
		$total				= count( $prioridades );
		if( $total ) :
			array_unshift( $prioridades, JHTML::_('select.option',$total+1, 'Próxima Prioridad (correlativo)') );
		else :
			$prioridades[]	= JHTML::_('select.option',$total+1, 'Próxima Prioridad (correlativo)');
		endif;
		
		array_unshift( $prioridades, JHTML::_('select.option', '', '- Prioridad -') );
		
		// genericlist( $arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = NULL, $idtag = false, $translate = false )
		return JHTML::_('select.genericlist', $prioridades, $nombre, $attribs, 'value', 'text', $valor );
	}
	
	function MultiSelecs( $tipo='', $nombre='', $valor=null, $attribs=null )
	{
		$valor					= is_array($valor) ? $valor : array($valor);
		$attribs				= 'multiple="multiple"'.$attribs;
		
		$id						= str_replace("[]","",$nombre);
		$nombre					= $id;
		
		switch( $tipo ) :
			case 'modulos' :
				$asignados		= GPTIHelperHtml::SelectReqsModulos( $nombre.'[]', $valor, $attribs, $id.'_asignados', true );
				$disponibles	= GPTIHelperHtml::SelectReqsModulos( $nombre.'_disponibles[]', $valor, $attribs, $id.'_disponibles' );
			break;
			case 'areas' :
				$asignados		= GPTIHelperHtml::SelectReqsAreas( $nombre.'[]', $valor, $attribs, $id.'_asignados', true );
				$disponibles	= GPTIHelperHtml::SelectReqsAreas( $nombre.'_disponibles[]', $valor, $attribs, $id.'_disponibles' );
			break;
			case 'valores' :
				$asignados		= GPTIHelperHtml::SelectReqsValores( $nombre.'[]', $valor, $attribs, $id.'_asignados', true );
				$disponibles	= GPTIHelperHtml::SelectReqsValores( $nombre.'_disponibles[]', $valor, $attribs, $id.'_disponibles' );
			break;
		endswitch;
		
		$html		= '<div>' . $asignados . '</div>' . "\n"
					. '<div class="flechas gpti_overflow">' . "\n"
					. '<div><a href="javascript:void(0);" onclick="javascript:GPTI_multiselect(\''.$id.'_asignados\',\''.$id.'_disponibles\'); return false;" title="Agregar"><img src="'.GPTI_TEMPLATE_URL.'imagenes/add.jpg" width="15" height="17" alt="Agregar" /></a></div>' . "\n"
					. '<div><a href="javascript:void(0);" onclick="javascript:GPTI_multiselect(\''.$id.'_disponibles\',\''.$id.'_asignados\'); return false;" title="Quitar"><img src="'.GPTI_TEMPLATE_URL.'imagenes/del.jpg" width="15" height="17" alt="Quitar" /></a></div>' . "\n"
					. '</div>' . "\n"
					. '<div>' . $disponibles . '</div>' . "\n"
					;
					
		return $html;
		/*
		<div>
			<select name="" multiple="multiple" id="mod_a">
				<option value="5">Proyecto e</option>
			</select>
		</div>
		<div class="flechas gpti_overflow">	
			<div>
				<a href="javascript:void(0);" onclick="javascript:multiselect('mod_a','mod_b'); return false;" title="Agregar">
					<img src="imagenes/add.jpg" width="15" height="17" alt="Agregar" /></a>
			</div>
			<div>
				<a href="javascript:void(0);" onclick="javascript:multiselect('mod_b','mod_a'); return false;" title="Quitar">
					<img src="imagenes/del.jpg" width="15" height="17" alt="Quitar" /></a>
			</div>
		</div>
		<div>
			<select name="" multiple="multiple" id="mod_b">
				<option value="1">Proyecto a</option>
				<option value="2">Proyecto b</option>
				<option value="3">Proyecto c</option>
				<option value="4">Proyecto d</option>
			</select>
		</div>
		*/
	}
	
	function SelectReqsModulos( $nombre='', $valor=null, $attribs=null, $id=null, $asignados=false )
	{
		
		$valorid = array();
		
		if(count( $valor )):
			foreach( $valor as $v ):
				$valorid[] = $v->MOD_ID ;
			endforeach;
		endif;
		
		$session		=& JFactory::getSession();
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN"
							." PKG_GPTI.PROC_REQS_MODULOS_LISTA"
							." ( :C_CURSOR );"
							." COMMIT; END;"
							;
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$c_tipos			= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$c_tipos, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		// option( $value, $text='', $value_name='value', $text_name='text', $disable=false )
		$modulos				= array();
		
		if( @!oci_execute( $c_tipos ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $row = oci_fetch_assoc( $c_tipos ) ) :
				if( ( !$asignados && array_search($row['MOD_ID'],$valorid) === false ) || ( $asignados && array_search($row['MOD_ID'],$valorid) !== false ) ) :
					$modulos[]	= JHTML::_('select.option',$row['MOD_ID'],$row['MOD_NOMBRE']);
				endif;
			endwhile;
			oci_free_statement( $c_tipos );
		endif;
		
		$seleccionados		= array();
		if( is_array( $valor ) && count( $valor ) ) :
			foreach( $valor as $key => $value ) :
				$seleccionados[]	= $value->MOD_ID;
			endforeach;
		endif;
		
		// genericlist( $arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = NULL, $idtag = false, $translate = false )
		return JHTML::_('select.genericlist', $modulos, $nombre, $attribs, 'value', 'text', $seleccionados, $id );
	}
	
	function SelectReqsAreas( $nombre='', $valor=null, $attribs=null, $id=null, $asignados=false )
	{
		
		$valorid = array();
		
		if(count( $valor )):
			foreach( $valor as $v ):
				$valorid[] = $v->ARE_ID ;
			endforeach;
		endif;
		
		$session		=& JFactory::getSession();
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN"
							." PKG_GPTI.PROC_REQS_AREAS_LISTA"
							." ( :C_CURSOR );"
							." COMMIT; END;"
							;
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$c_tipos			= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$c_tipos, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		// option( $value, $text='', $value_name='value', $text_name='text', $disable=false )
		$areas				= array();
		
		if( @!oci_execute( $c_tipos ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $row = oci_fetch_assoc( $c_tipos ) ) :
				if( ( !$asignados && array_search($row['ARE_ID'],$valorid) === false ) || ( $asignados && array_search($row['ARE_ID'],$valorid) !== false ) ) :
					$areas[]	= JHTML::_('select.option',$row['ARE_ID'],$row['ARE_NOMBRE']);
				endif;
			endwhile;
			oci_free_statement( $c_tipos );
		endif;
		
		$seleccionados		= array();
		if( is_array( $valor ) && count( $valor ) ) :
			foreach( $valor as $key => $value ) :
				$seleccionados[]	= $value->ARE_ID;
			endforeach;
		endif;
		
		// genericlist( $arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = NULL, $idtag = false, $translate = false )
		return JHTML::_('select.genericlist', $areas, $nombre, $attribs, 'value', 'text', $seleccionados, $id );
	}
	
	function SelectReqsValores( $nombre='', $valor=null, $attribs=null, $id=null, $asignados=false )
	{
		
		$valorid = array();
		
		if(count( $valor )):
			foreach( $valor as $v ):
				$valorid[] = $v->VAS_ID ;
			endforeach;
		endif;
		
		
		$session		=& JFactory::getSession();
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN"
							." PKG_GPTI.PROC_REQS_VALORES_LISTA"
							." ( :C_CURSOR );"
							." COMMIT; END;"
							;
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$c_tipos			= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$c_tipos, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		// option( $value, $text='', $value_name='value', $text_name='text', $disable=false )
		$valores				= array();
		
		if( @!oci_execute( $c_tipos ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $row = oci_fetch_assoc( $c_tipos ) ) :
				if( ( !$asignados && array_search($row['VAS_ID'],$valorid) === false ) || ( $asignados && array_search($row['VAS_ID'],$valorid) !== false ) ) :
					$valores[]	= JHTML::_('select.option',$row['VAS_ID'],$row['VAS_NOMBRE']);
				endif;
			endwhile;
			oci_free_statement( $c_tipos );
		endif;
		
		$seleccionados		= array();
		if( is_array( $valor ) && count( $valor ) ) :
			foreach( $valor as $key => $value ) :
				$seleccionados[]	= $value->VAS_ID;
			endforeach;
		endif;
		
		// genericlist( $arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = NULL, $idtag = false, $translate = false )
		return JHTML::_('select.genericlist', $valores, $nombre, $attribs, 'value', 'text', $seleccionados, $id );
	}
	
	function SelectTareaTipos( $nombre='', $valor=null, $attribs=null )
	{
		$session		=& JFactory::getSession();
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
							
		$storeprocedure		= "BEGIN"
							." PKG_GPTI.PROC_TAREA_TIPOS_LISTA"
							." ( :C_CURSOR );"
							." COMMIT; END;"
							;
							
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$c_cursor			= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$c_cursor, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		// option( $value, $text='', $value_name='value', $text_name='text', $disable=false )
		$items				= array( JHTML::_('select.option','','- Tipos -') );
		
		if( @!oci_execute( $c_cursor ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $row = oci_fetch_assoc( $c_cursor ) ) :
				$items[]	= JHTML::_('select.option',$row['TAT_ID'],$row['TAT_NOMBRE']);
			endwhile;
			oci_free_statement( $c_cursor );
		endif;
		
		// genericlist( $arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = NULL, $idtag = false, $translate = false )
		return JHTML::_('select.genericlist', $items, $nombre, $attribs, 'value', 'text', $valor );
	}
	
	function SelectTareaEjecutores( $nombre='', $valor=null, $attribs=null, $proveedor=null )
	{
		$session		=& JFactory::getSession();
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		$GPTIuser		=& $session->get( 'GPTI_user', null );
							
		$storeprocedure		= "BEGIN"
							." PKG_GPTI.PROC_USER_LISTA"
							." ( :P_ERROR, :P_UID, :P_PERFIL, :P_ROL, :P_GERENCIA, :P_PROVEEDOR, :C_CURSOR );"
							." COMMIT; END;"
							;
							
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$error				= null;
		oci_bind_by_name( $stmt, ':P_ERROR',			$p_usuario,			32 );
		$p_uid				= null;
		oci_bind_by_name( $stmt, ':P_UID',				$p_uid,				40 );
		$p_perfil			= 6;
		oci_bind_by_name( $stmt, ':P_PERFIL',			$p_perfil,			32 );
		$p_rol				= null;
		oci_bind_by_name( $stmt, ':P_ROL',				$p_rol,				40 );
		$p_gerencia			= null;
		oci_bind_by_name( $stmt, ':P_GERENCIA', 		$p_gerencia,		40 );
		$p_proveedor		= $GPTIuser->USR_PROVEEDOR ? $GPTIuser->USR_PROVEEDOR : $proveedor;
		oci_bind_by_name( $stmt, ':P_PROVEEDOR', 				$p_proveedor,		40 );
		//oci_bind_by_name( $stmt, ':P_PROVEEDOR', 				$vars['dst'],		40 );
		$c_cursor			= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$c_cursor, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		// option( $value, $text='', $value_name='value', $text_name='text', $disable=false )
		$items		= array( JHTML::_('select.option','','- Ejecutor -') );
		
		if( @!oci_execute( $c_cursor ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $row = oci_fetch_assoc( $c_cursor ) ) :
				unset($user);
				$user = 	clone(JFactory::getUser());
				$user->load( (int)$row['USR_JOOMLA'] );
				$items[]	= JHTML::_('select.option',$row['USR_ID'],$user->get('name'));
			endwhile;
			oci_free_statement( $c_cursor );
		endif;
		
		// genericlist( $arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = NULL, $idtag = false, $translate = false )
		return JHTML::_('select.genericlist', $items, $nombre, $attribs, 'value', 'text', $valor );
	}
	
	function SelectReqProveedores( $nombre='', $valor=null, $attribs=null )
	{
		$session		=& JFactory::getSession();
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		$GPTIuser		=& $session->get( 'GPTI_user', null );
							
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_REQS_PROVEEDORES_LISTA ( :C_CURSOR );  END;";
							
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$c_cursor			= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$c_cursor, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		if( $error ) :
			//echo $error; exit;
			GPTIHelperError::Raise( htmlentities($error, ENT_QUOTES) );
			return false;
		endif;
		
		// option( $value, $text='', $value_name='value', $text_name='text', $disable=false )
		$items		= array( JHTML::_('select.option','','- Proveedor -') );
		
		if( @!oci_execute( $c_cursor ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $row = oci_fetch_assoc( $c_cursor ) ) :
				$items[]	= JHTML::_('select.option',$row['PRO_ID'],$row['PRO_NOMBRE']);
			endwhile; 
			oci_free_statement( $c_cursor );
		endif;
		
		// genericlist( $arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = NULL, $idtag = false, $translate = false )
		return JHTML::_('select.genericlist', $items, $nombre, $attribs, 'value', 'text', $valor );
	}
	
	function SelectReqEncargados( $nombre='', $valor=null, $attribs=null )
	{
		$session		=& JFactory::getSession();
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
							
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_REQS_ENCARGADOS_LISTA ( :C_CURSOR ); END;";
							
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$c_cursor			= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$c_cursor, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		// option( $value, $text='', $value_name='value', $text_name='text', $disable=false )
		$items				= array( JHTML::_('select.option','','- Encargado -') );
		
		if( @!oci_execute( $c_cursor ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $row = oci_fetch_assoc( $c_cursor ) ) :
				unset($user);
				$user = 	clone(JFactory::getUser());
				$user->load( (int)$row['USR_JOOMLA'] );
				$items[]	= JHTML::_('select.option',$row['USR_ID'],$user->get('name'));
			endwhile;
			oci_free_statement( $c_cursor );
		endif;
		
		// genericlist( $arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = NULL, $idtag = false, $translate = false )
		return JHTML::_('select.genericlist', $items, $nombre, $attribs, 'value', 'text', $valor );
	}
	
	function selectFile( $nombre='', $valor='', $class='', $attribs=null )
	{
		return '<input type="file" name="'.$nombre.'" class="'.$class.'" value="'.$valor.'" '.$attribs.'/>';
	}
	
	function SelectCambiosPrioridad( $nombre='', $valor=null, $attribs=null )
	{
		$session		=& JFactory::getSession();
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
							
		$storeprocedure		= "BEGIN"
							." PKG_GPTI.PROC_REQ_CP_LISTA"
							." ( :P_GERENCIA, :C_CURSOR );"
							." COMMIT; END;"
							;
							
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		$p_gerencia			= null;
		oci_bind_by_name( $stmt, ':P_GERENCIA', 	$p_gerencia, 40 );
		
		$c_cursor			= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$c_cursor, -1, OCI_B_CURSOR );
		
		if( !oci_execute( $stmt ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		endif;
		
		// option( $value, $text='', $value_name='value', $text_name='text', $disable=false )
		$items				= array( JHTML::_('select.option','','- Gerencia -') );
		
		if( @!oci_execute( $c_cursor ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			exit;
		else:
			while ( $row = oci_fetch_assoc( $c_cursor ) ) :
				$items[]	= JHTML::_('select.option',$row['RCP_ID'],$row['GER_NOMBRE']);
			endwhile;
			oci_free_statement( $c_cursor );
		endif;
		
		if( count($items)==1 ):
			return 'No hay solicitudes que procesar.';
		endif;
		
		// genericlist( $arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = NULL, $idtag = false, $translate = false )
		return JHTML::_('select.genericlist', $items, $nombre, $attribs, 'value', 'text', $valor );
	}
	
	function ListaUltimos( $filtro=array(), $limite=0 )
	{
			
		$session		=& JFactory::getSession();
		$GPTIuser		=& $session->get( 'GPTI_user', null );
		$GPTIconn		=& $session->get( 'GPTI_conn', null );
		
		$storeprocedure		= "BEGIN PKG_GPTI.PROC_REQS_BUSCAR ( :P_ERROR, :P_ID, :P_PROYECTO, :P_NOMBRE, :P_FECHA_DESDE, :P_FECHA_HASTA, :P_ESTADO, :P_DRU, :P_TIPO, :P_CLASIFICACION, :P_PROVEEDOR, :P_GERENCIA, :P_NRO_INTERNO, :P_USUARIO, :P_FASE, :P_FASE_DESDE, :P_FASE_HASTA, :P_PLAZO, :P_ENCARGADO, :P_ORDEN, :C_CURSOR ); END;";
		if( !$stmt			= oci_parse( $GPTIconn, $storeprocedure ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;
		
		if( $GPTIuser->USR_PERFIL == 5 || $GPTIuser->USR_PERFIL == 6 ) :
			$filtro['orden']	= "ORDER BY REQS.REQ_PRIORIDAD_PROV ASC, REQS.REQ_FECHA_CREACION DESC";
		endif;
		
		//$this->REQ_GERENCIA 	= $GPTIuser->USR_GERENCIA;
		//$this->REQ_PROVEEDOR 	= $GPTIuser->USR_PROVEEDOR;
		
		$error						= null;
		oci_bind_by_name( $stmt, ':P_ERROR',			$error,								1000 );
		oci_bind_by_name( $stmt, ':P_ID',				$filtro['REQ_ID'],					40 );
		oci_bind_by_name( $stmt, ':P_PROYECTO',			$filtro['REQ_PROYECTO'],			40 );
		oci_bind_by_name( $stmt, ':P_NOMBRE',			$filtro['REQ_NOMBRE'],				40 );
		oci_bind_by_name( $stmt, ':P_FECHA_DESDE',		$filtro['REQ_FECHA_DESDE'],			40 );
		oci_bind_by_name( $stmt, ':P_FECHA_HASTA',		$filtro['REQ_FECHA_HASTA'],			40 );
		oci_bind_by_name( $stmt, ':P_ESTADO',			$filtro['REQ_ESTADO'],				40 );
		oci_bind_by_name( $stmt, ':P_DRU',				$filtro['REQ_DRU'],					40 );
		oci_bind_by_name( $stmt, ':P_TIPO',				$filtro['REQ_TIPO'],				40 );
		oci_bind_by_name( $stmt, ':P_CLASIFICACION',	$filtro['REQ_CLASIFICACION'],		40 );
		oci_bind_by_name( $stmt, ':P_PROVEEDOR',		$filtro['REQ_PROVEEDOR'],			40 );
		oci_bind_by_name( $stmt, ':P_GERENCIA',			$filtro['REQ_GERENCIA'],			40 );
		oci_bind_by_name( $stmt, ':P_NRO_INTERNO',		$filtro['REQ_NRO_INTERNO'],			40 );
		oci_bind_by_name( $stmt, ':P_USUARIO',			$filtro['REQ_USUARIO'],				40 );
		oci_bind_by_name( $stmt, ':P_FASE',				$filtro['REQ_FASE'],				40 );
		oci_bind_by_name( $stmt, ':P_FASE_DESDE',		$filtro['REQ_FASE_DESDE'],			40 );
		oci_bind_by_name( $stmt, ':P_FASE_HASTA',		$filtro['REQ_FASE_HASTA'],			40 );
		oci_bind_by_name( $stmt, ':P_PLAZO',			$filtro['REQ_PLAZO'],				40 );
		oci_bind_by_name( $stmt, ':P_ENCARGADO',		$filtro['REQ_ENCARGADO'],			40 );
		oci_bind_by_name( $stmt, ':P_ORDEN',			$filtro['orden'],					1000 );
		
		$c_cursor			= oci_new_cursor( $GPTIconn );
		oci_bind_by_name( $stmt, ':C_CURSOR', 		$c_cursor, -1, OCI_B_CURSOR );
		if( !oci_execute( $stmt ) ) :
			//echo oci_error(); exit;
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return false;
		endif;

		if( $error ) :
			//echo $error; exit;
			GPTIHelperError::Raise( htmlentities($error, ENT_QUOTES) );
			return false;
		endif;
		
		$rows		= array();
		
		if( @!oci_execute( $c_cursor ) ) :
			$e 				= oci_error();
			GPTIHelperError::Raise( htmlentities($e['message'], ENT_QUOTES) );
			return array();
		else:
		
			$db				= JFactory::getDBO();
			$query			= "SELECT mm.id"
							. " FROM #__components AS com"
							. "   LEFT JOIN #__menu AS mm ON mm.componentid = com.id"
							. " WHERE com.enabled = 1"
							. "   AND com.option = 'com_gpti'"
							. "   AND com.parent = 0"
							. "   AND mm.published = 1"
							. "   AND mm.parent = 0"
							. "   AND mm.params LIKE '%controlador=requerimientos%'"
							. "   AND mm.params LIKE '%tarea=buscar%'"
							. " ORDER BY com.ordering ASC, mm.ordering ASC"
							;
			$db->setQuery($query);
			$lists['menu-buscar']			= (int)$db->loadResult();
		
			while ( $fila = oci_fetch_assoc( $c_cursor ) ) :
			unset($row);
			$row			= new stdClass();
				foreach( $fila as $key => $value ) :
					$row->$key		= $value;
				endforeach;
				$row->REQ_LINK = JRoute::_( "index.php?Itemid=".$lists['menu-buscar']."&task=ver&REQ_ID=".$row->REQ_ID );

			$rows[]			= $row;
			endwhile;
			oci_free_statement( $c_cursor );
		endif;
		
		if( !count( $rows ) )
			return '';
			
		if( $limite ) :
			$rows	= array_splice( $rows, 0, $limite );
		endif;
		
		$html = ''
		.		'<table cellpadding="0" cellspacing="0" border="0" class="gpti_listado gpti_blanco">' . "\n"
		;
		foreach( $rows as $row ) :
			$html .=''
			.		'	<tr>' . "\n"
			.		'		<td class="gpti_dru" nowrap="nowrap">[' . $row->REQ_DRU . ']</td>' . "\n"
			.		'		<td class="gpti_titulo"><a href="' . $row->REQ_LINK . '" title="' . $row->REQ_NOMBRE . '">' . $row->REQ_NOMBRE . '</a></td>' . "\n"
			.		'	</tr>' . "\n"
			;
		endforeach;
		$html .=''
		.		'</table>' . "\n"
		;
		
		return $html;
	}
		
}
?>