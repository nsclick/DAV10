<?php
/**
* @version		$Id: do.php 2010-04-07 Sebastián García Truan $
* @package		Joomla.Framework
* @subpackage	HTML
* @autor		Diseño Objetivo - www.disenobjetivo.cl - disenobjetivo@disenobjetivo.cl
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
	
/**
 * Clase DO HTML. Utilizada para crear elementos de formulario y html en general
 *
 * @static
 * @package 	Joomla.Framework
 * @subpackage	HTML
 * @since		1.5
 */
class JHTMLDo
{
	/**
	 * Agrega el JavaScript
	 *
	 * @access		publico
	 * @parametro	$mootools - buleano - incluir mootools
	 */
	function js( $mootools=false )
	{
		JHTML::script('do.js', 'media/system/js/', $mootools);	
	}
	
	/**
	 * Agrega el CSS
	 *
	 * @access		publico
	 */
	function css()
	{
		JHTML::stylesheet('do.css');	
	}
	
	/**
	 * Agrega la clase JavaScript jQuery
	 *
	 * @access		publico
	 */
	function jQuery()
	{
		JHTML::script('jquery-1.4.2.min.js', 'media/system/js/', false);
		
		$jqueryfix		= "jQuery.noConflict();";
		
		$document = &JFactory::getDocument();
		$document->addScriptDeclaration( $jqueryfix );
		return true;
	}
		
	/**
	 * Crea un campo text o areatexto, con Label
	 *
	 * @access		publico
	 * @parametro	$tipo - cadena - text, textarea, file
	 * @parametro	$nombre - cadena - Nombre del campo
	 * @parametro	$valor - cadena - Valor por defecto
	 * @parametro	$label - cadena - Label del campo
	 * @parametro	$id - buleano/cadena - false o Id del campo
	 * @parametro	$attribs - cadena/array - Cadena con atributos, tambien puede ser un arreglo
	 * @retorno		cadena - html
	 */
	function form_texto( $tipo='text', $nombre='campo', $valor='', $label='Campo', $attribs=null, $id=true )
	{
		// se requiere el JavaScript
		JHTMLDo::js();
		
		$html		= '';
		$id			= $id && is_bool( $id ) ? $nombre : $id;
		$valor		= $valor ? $valor : $label;
		$attribs	= $attribs && is_array( $attribs ) ? JArrayHelper::toString( $attribs ) : $attribs;
		
		switch( $tipo )
		{
			case 'text' :
				$html		= '<input'
							. ' type="text"'
							. ' name="' . $nombre . '"'
							. ( $id ? ' id="'  .$id . '"' : '' )
							. ' title="' . $label . '"'
							. ' value="' . $valor . '"'
							//. ' onblur="form_texto_blur(this);"'
							//. ' onfocus="form_texto_focus(this);"'
							. ( $attribs ? ' ' . $attribs : '' )
							. ' />'
							;
			break;
			case 'textarea' :
				$html		= '<textarea'
							. ' name="' . $nombre . '"'
							. ( $id ? ' id="'  .$id . '"' : '' )
							. ' title="' . $label . '"'
							//. ' onblur="form_texto_blur(this);"'
							//. ' onfocus="form_texto_focus(this);"'
							. ( $attribs ? ' ' . $attribs : '' )
							. '>' . $valor . '</textarea>'
							;
			break;
			case 'file' :
				$html		= '<input'
							. ' type="file"'
							. ' name="' . $nombre . '"'
							. ' title="' . $label . '"'
							. ( $id ? ' id="'  .$id . '"' : '' )
							//. ' value="' . $valor . '"'
							//. ' onblur="form_texto_blur(this,\'' . $label . '\');"'
							//. ' onfocus="form_texto_focus(this,\'' . $label . '\');"'
							. ( $attribs ? ' ' . $attribs : '' )
							. ' />'
							;
			break;
		}
		
		return $html;
	}
	
	/**
	 * Crea un arreglo de un campo, presidido por un text con label (form_texto)
	 *
	 * @access		publico
	 * @parametro	$hash - cadena - str, int, float
	 * @parametro	$orden - cadena - asc, desc, usuario
	 * @parametro	$nombre - cadena - Nombre del campo
	 * @parametro	$valores - arreglo - Valores por defecto
	 * @parametro	$label - cadena - Label del campo
	 * @parametro	$attribs - cadena/array - Cadena con atributos, tambien puede ser un arreglo
	 * @retorno		cadena - html
	 */
	function form_arreglo( $hash='str', $orden='', $nombre='campo', $valores=array(), $label='Campo', $attribs=null, $file_base='' )
	{
		// se requiere el JavaScript
		JHTMLDo::js(true);
		
		// se requiere el CSS
		JHTMLDo::css();
		
		/*$js			= "var keywords = new Form_Arreglo('<?php echo implode("', '", $lists['keywords']->parametros);?>');";*/

		$doc		= JFactory::getDocument();
		$script 	= ""
					. "window.addEvent('domready', function() {\n"
					. "\tvar $nombre = new Form_Arreglo('$hash', '$orden', '$nombre', '$label', '$file_base');\n"
					. "\t$('do_form_arreglo_".$nombre."_plus').addEvent('click', function(e){\n"
					. "\t\t$nombre.agregar();\n"
					. "\t\treturn false;\n"
					. "\t});\n"
					//. "\t$nombre.reordena();\n"
					;
					if( count( $valores ) )
					{
						foreach( $valores as $valor )
						{
							if( $valor )
							{
								$script 	.= "\n$nombre.valor	='$valor'; $nombre.agrega();";
							}
						}
					}
		$script 	.= ""
					. "});\n\n"
					;
					
		$doc->addScriptDeclaration($script);
		$indiceArreglo	= -1;
		
		$html		= "<div class=\"do_form_arreglo\">\n"
						. "\t<div class=\"do_form_arreglo_entrada\">\n"
							. "\t\t<div style=\"float:left;\">" . JHTMLDo::form_texto( $hash=='file'?'file':'text', $nombre, $label, $label, $nombre, $attribs ) . "</div>\n"
							//. "\t\t<div class=\"boton\" style=\"float:left; width:30px; margin-left:5px;\"><a href=\"javascript:void(0);\" onclick=\"javascript:form_arreglo_agregar('" . $hash . "', '" . $nombre . "', '" . $orden . "', '" . $label . "' ); return false;\" title=\"+\">+</a></div>\n"
							//. "\t\t<div class=\"boton\" style=\"float:left; width:30px; margin-left:5px;\"><a href=\"javascript:void(0);\" onclick=\"javascript:$nombre.agregar(document.adminForm.$nombre); return false;\" title=\"+\">+</a></div>\n"
							. "\t\t<div class=\"boton\" style=\"float:left; width:30px; margin-left:5px;\"><a href=\"javascript:void(0);\" id=\"do_form_arreglo_".$nombre."_plus\" title=\"+\">+</a></div>\n"
						. "\t</div>\n"
						. "\t<div class=\"do_form_arreglo_contenedor\" id=\"do_form_arreglo_contenedor_$nombre\">\n"
						;
						/*if( count( $valores ) )
						{
							foreach( $valores as $valor )
							{
								if( $valor )
								{
									++$indiceArreglo;
									$ordenhtml	= "";
									if( $orden == 'usuario' )
									{
										$ordenhtml	.= "\t\t\t<div class=\"do_form_arreglo_orden\" align=\"center\" id=\"do_form_arreglo_orden_".$nombre."_".$indiceArreglo."\">";
										//$ordenhtml	.= $indiceArreglo ? '<a html="javascript:void(0);" onclick="javascript:form_arreglo_cambiaOrden(\'' . $nombre . '\','.$indiceArreglo.','.$indiceArreglo-1.'); return false;" title="Subir">&uarr;</a>' : '';
										//$ordenhtml	.= $indiceArreglo < count( $valores ) - 1 ? '<a html="javascript:void(0);" onclick="javascript:form_arreglo_cambiaOrden(\'' . $nombre . '\','.$indiceArreglo.','.$indiceArreglo+1.'); return false;" title="Bajar">&darr;</a>' : '';
										$ordenhtml	.= "</div>\n";
									}
									$html 	.= "\t\t<div class=\"do_form_arreglo_subcontenedor\" id=\"do_form_arreglo_subcontenedor_".$nombre."_".$indiceArreglo."\">\n"
												. "\t\t\t<div class=\"do_form_arreglo_texto\" id=\"do_form_arreglo_texto_".$nombre."_".$indiceArreglo."\">" . $valor . "<input type=\"hidden\" name=\"".$nombre."[]\" id=\"do_form_arreglo_".$nombre."_".$indiceArreglo."\" value=\"" . $valor . "\" /></div>\n"
												. $ordenhtml
												. "\t\t\t<div class=\"do_form_arreglo_eliminar\" align=\"center\"><a href=\"javascript:void(0);\" onclick=\"javascript:$nombre.eliminar(" . $indiceArreglo . "); return false;\" title=\"Eliminar\" onmouseout=\"javascript:MM_swapImgRestore();\" onmouseover=\"javascript:MM_swapImage('do_form_arreglo_eliminar_".$nombre."_".$indiceArreglo."_img','','images/do/form/eliminar1.png',1);\"><img src=\"images/do/form/eliminar0.png\" alt=\"eliminar\" id=\"do_form_arreglo_eliminar_".$nombre."_".$indiceArreglo."_img\" border=\"0\" /></a></div>\n"
											. "\t\t</div>\n"
											;
								}
							}
						}*/
						//'do_form_arreglo_eliminar_' + this.nombre + '_' + indiceArreglo + '_img'
		$html		.= "\t</div>\n"
					. "\t<input type=\"hidden\" name=\"do_form_arreglo_indice_$nombre\" id=\"do_form_arreglo_indice_$nombre\" value=\"$indiceArreglo\" />\n"
					. "</div>\n"
					/*. "<script type=\"text/javascript\"> $nombre.reordena(); </script>"*/
					;
		
		return $html;
	}
	
	/**
	 * Crea un campo checkbox, con Label
	 *
	 * @access		publico
	 * @parametro	$nombre - cadena - Nombre del campo
	 * @parametro	$valor - cadena - Valor por defecto
	 * @parametro	$label - cadena - Label del campo
	 * @parametro	$attribs - cadena/array - Cadena con atributos, tambien puede ser un arreglo
	 * @retorno		cadena - html
	 */
	function form_check( $nombre='campo', $valor=1, $estado=0, $label='Campo', $attribs=null )
	{
		$html		= '';
		$attribs	= $attribs && is_array( $attribs ) ? JArrayHelper::toString( $attribs ) : $attribs;
		
		$html		.= '<input'
					. ' type="checkbox"'
					. ' name="' . $nombre . '"'
					. ' id="' . $nombre . '"'
					. ' value="' . $valor . '"'
					. ( $estado ? ' checked="checked"' : '' )
					. ( $attribs ? ' ' . $attribs : '' )
					. ' />'
					. ' <label for="' . $nombre . '">' . $label . '</label>'
					;
					
		return $html;
	}
	
	
	
	/**
	* Build the select list for access level
	*/
	function accesslevel( &$SCMI, &$row )
	{
		$access = JHTML::_('select.genericlist', $SCMI->getPerfiles(), 'access', 'class="inputbox" size="6"', 'acceso', 'nombre', intval( $row->access ), '', 1 );
		return $access;
	}

	function grid_access( &$SCMI, &$row, $i, $archived = NULL )
	{
		$color_access = 'style="color: red;"';
		
		$perfiles = $SCMI->getPerfiles();
		if( count( $perfiles ) )
		{
			foreach( $perfiles as $perfil )
			{
				if( $row->access == $perfil->acceso )
				{
					$row->groupname 	= $perfil->nombre;
					$task_access		= "access" . ( ($perfil->access==5) ? 0 : (int)$perfil->access + 1);
					if( !$perfil->access )
					{
						$color_access = 'style="color: green;"';	
					}
				}
			}
		}
		
		$href = '
		<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $task_access .'\')" '. $color_access .'>
		'. JText::_( $row->groupname ) .'</a>'
		;

		return $href;
	}
	
	function acl_groups( $nombre = '', $valor = 0, $attribs = null )
	{
		$db			=& JFactory::getDBO();
		
		$grupos		= array();
		JHTMLScmi::getListaDesendencia( $grupos, array("where"=>"","order"=>"\n ORDER BY g.name ASC"), 28 );
		array_unshift( $grupos, JHTML::_('select.option', 0, '- Seleccionar Grupo -', 'id', 'htmlname' ) );
		
		$attribs	= ( $attribs == null ) ? array("class"=>"inputbox") : $attribs;
		$aclgrupos	= JHTML::_('select.genericlist', $grupos, $nombre, $attribs, 'id', 'htmlname', intval( $valor ) );
		return $aclgrupos;
	}
	
	function getListaDesendencia( &$arreglo, $sentencia = array(), $padre = 0, $nivel = 0 )
	{
		$db			=& JFactory::getDBO();
		$proxnivel 	= (int) $nivel + 1;
		$pre    	= '<sup>|_</sup>&nbsp;';
		$spacer 	= '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		
		$intro		= '';
		if( $nivel )
		{
			for( $i=$nivel; $i>0; --$i )
			{
				$intro	.= $spacer;
			}
			$intro	.= $pre;
		}
		
		$where		= $sentencia["where"];
		if( !ereg( "g.parent_id=", $where ) && !ereg( "g.parent_id =", $where ) )
		{
			$where .= ( $where != "" ) ? "\n AND g.parent_id=$padre" : "\n WHERE g.parent_id=$padre";
		}
		$order		= $sentencia["order"];
		
		$query = "SELECT g.*, COUNT(gg.id) as hijos"
		. "\n FROM #__core_acl_aro_groups as g"
		. "\n LEFT JOIN #__core_acl_aro_groups as gg ON gg.parent_id = g.id"
		. $where
		. "\n GROUP BY g.id"
		. $order
		;
		$db->setQuery( $query );
		if( count( $items = $db->loadObjectList() ) )
		{
			foreach( $items	as $item )
			{
				$item->nivel		= $nivel;
				$item->htmlname		= $intro . $item->name;
				$arreglo[]			= $item;
				if( $item->hijos )
				{
					JHTMLScmi::getListaDesendencia( $arreglo, $sentencia, $item->id, $proxnivel );
				}
			}
		}
	}

	/**
	 * Muestra una caja de texto con calendario
	 *
	 * @param	string	El valor de la fecha
	 * @param	string	El nombre de la caja de texto
	 * @param	string	El id de la caja de texto
	 * @param	string	El formato de la fecha
	 * @param	array	Atributos html adicionales
	 */
	function calendario($valor, $nombre, $id, $formato = '%Y-%m-%d', $attribs = null, $label = '')
	{
		JHTML::_('behavior.calendar'); //cargamos el calendario
		
		if (is_array($attribs)) {
			if( isset( $attribs['style'] ) )
			{
				$attribs['style']	= ( !ereg( 'float', $attribs['style'] ) ) ? $attribs['style'] . ' float:left;' : $attribs['style'];
				$attribs['style']	= ( !ereg( 'height', $attribs['style'] ) ) ? $attribs['style'] . ' height:16px;' : $attribs['style'];
			}
			else
			{
				$attribs['style']	= 'float:left; height:16px;';
			}
			$attribs = JArrayHelper::toString( $attribs );
		}
		$document =& JFactory::getDocument();
		$document->addScriptDeclaration('window.addEvent(\'domready\', function() {Calendar.setup({
        inputField     :    "'.$id.'",     // id of the input field
        ifFormat       :    "'.$formato.'",      // format of the input field
        button         :    "'.$id.'_img",  // trigger for the calendar (button ID)
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true
    });});');

		$valor			= ( $valor ) ? $valor : $label;
		
		return '<input type="text" name="'.$nombre.'" title="'.$label.'" id="'.$id.'" value="'.htmlspecialchars($valor, ENT_COMPAT, 'UTF-8').'" onblur="javascript:objform_blur(this);" onfocus="javascript:objform_focus(this);" '.$attribs.' />'.
				 '<img class="calendar" src="'.JURI::root(true).'/images/calendario.jpg" alt="calendario" id="'.$id.'_img" style="float:left;" />';
	}
	
	function teclado( $tipo='N', $nombre='', $id='', $valor=null, $label='', $attribs=null )
	{
		/*
<input type="text" name="filtro_km_hasta" id="filtro_km_hasta" value="Hasta" onblur="objform_blur(this,'Hasta');" onfocus="objform_focus(this,'Hasta');" class="componente_inputbox" style="width:60px;" /><a href="#" title="Teclado Numérico"><img src="<?php echo $_SCMI->_urlbase; ?>/imagenes/boton_teclado.jpg" alt="TN" title="Teclado Numérico" border="0" /></a>

<input type="text" name="nombre" id="nombre" value="Nombre" onblur="objform_blur(this,'Nombre');" onfocus="objform_focus(this,'Nombre');" class="componente_inputbox" size="40" /><a href="#" title="Teclado Alfabético"><img src="<?php echo $_SCMI->_urlbase; ?>/imagenes/boton_teclado.jpg" alt="TA" title="Teclado Alfabético" border="0" /></a>
		*/
		
		if (is_array($attribs)) {
			if( isset( $attribs['style'] ) )
			{
				$attribs['style']	= ( !ereg( 'width', $attribs['style'] ) ) ? $attribs['style'] . ' width:60px;' : $attribs['style'];
			}
			else
			{
				$attribs['style']	= 'width:60px;';
			}
			$attribs = JArrayHelper::toString( $attribs );
		}
		
		$valor			= ( $valor ) ? $valor : $label;
		
		switch( $tipo )
		{
			case 'A':
				$link	= '<a href="javascript:void(0);" onclick="javascript:teclado(this, \'alfabetico\', \''.$id.'\', \''.$label.'\'); return false;" title="Teclado Alfabético" id="'.$id.'_link"><img src="'.JURI::root(true).'/images/teclado.jpg" alt="TA" title="Teclado Alfabético" border="0" /></a>';
				break;
			case 'N':
			default:
				$link	= '<a href="javascript:void(0);" onclick="javascript:teclado(this, \'numerico\', \''.$id.'\', \''.$label.'\'); return false;" title="Teclado Numérico" id="'.$id.'_link"><img src="'.JURI::root(true).'/images/teclado.jpg" alt="TN" title="Teclado Numérico" border="0" /></a>';
				break;
		}
		
		return '<input type="text" name="'.$nombre.'" id="'.$id.'" value="'.$valor.'" onblur="objform_blur(this,\''.$label.'\');" onfocus="objform_focus(this,\''.$label.'\');" '.$attribs.' />' . $link;
	}
	
	function linkinforme( &$SCMI, $row )
	{
		global $Itemid;
		$app					=& JFactory::getApplication();
		$templateurl			= JURI::base() . 'templates/' . $app->getTemplate();
		
		if( $row->estado == 'cerrada' )
		{
			return '<a href="'.JRoute::_("index.php?option=".$SCMI->get('_option')."&c=cierres&task=ver&id=".$row->id."&Itemid=".$SCMI->getConfig('menu_actividades', $Itemid)).'" title="Informe Cierre"><img src="'.$templateurl.'/imagenes/componente_tabla_descarga.gif" alt="" width="16" height="16" border="0" /></a>';
		}
		
		return;
	}

	/**
	* Generates an HTML radio list
	*
	* @param array An array of objects
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @param string The name of the object variable for the option value
	* @param string The name of the object variable for the option text
	* @returns string HTML for the select list
	*/
	function radiolist( $arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = null, $idtag = false, $translate = false )
	{
		reset( $arr );
		$html = '';

		if (is_array($attribs)) {
			$attribs = JArrayHelper::toString($attribs);
		 }

		$id_text = $name;
		if ( $idtag ) {
			$id_text = $idtag;
		}

		for ($i=0, $n=count( $arr ); $i < $n; $i++ )
		{
			$k	= $arr[$i]->$key;
			$t	= $translate ? JText::_( $arr[$i]->$text ) : $arr[$i]->$text;
			$id	= ( isset($arr[$i]->id) ? @$arr[$i]->id : null);

			$extra	= '';
			$extra	.= $id ? " id=\"" . $arr[$i]->id . "\"" : '';
			if (is_array( $selected ))
			{
				foreach ($selected as $val)
				{
					$k2 = is_object( $val ) ? $val->$key : $val;
					if ($k == $k2)
					{
						$extra .= " selected=\"selected\"";
						break;
					}
				}
			} else {
				$extra .= ((string)$k == (string)$selected ? " checked=\"checked\"" : '');
			}
			$html .= "\n\t".($i?'<br />':'')."<input type=\"radio\" name=\"$name\" id=\"$id_text$k\" value=\"".$k."\"$extra $attribs />";
			$html .= "\n\t<label for=\"$id_text$k\">$t</label>";
		}
		$html .= "\n";
		return $html;
	}

	function radioTipoEjecutor( $nombre='ejecutor_tipo', $selected=null, $attribs=null )
	{
		$tipos		= array( JHTML::_('select.option', 'cuadrilla', 'Cuadrilla', 'valor', 'texto' ), JHTML::_('select.option', 'contratista', 'Contratista', 'valor', 'texto' ) );
		return JHTMLScmi::radiolist( $tipos, $nombre, $attribs, 'valor', 'texto', $selected );
	}

}
