/**
* @version		$Id: gpti.js 2011-06-01 Sebastián García Truan $
* @package		GPTI JavaScript
* @subpackage	DO
* @autor		Diseño Objetivo - www.do.cl - info@do.cl
* @copyright	Copyright (C) 2006 - 2010 Diseño Objetivo. Todos los derechos reservados.
* @license		LICENCIA_DO.php
*/

/**
 * Funciones JavaScript
 *
 */
 	// gpti_centro' , 'gpti_izquierda', 'gpti_derecha	
	function admColumnasReporte( central, right, left, label )
	{
    	if(jQuery('#'+label).text() == 'Cerrar')
		{
			jQuery('#'+label).text('Abrir');
			jQuery('div.'+right).animate({ width : 0, opacity : 0 });
			jQuery('div.'+left).animate({ width : 0, opacity : 0 });
			jQuery('div.'+central).animate({ width : 770 });
			jQuery.cookie('reporte', 'reportecerrar', { expires: 365 });
		}
		else
		{
			jQuery('#'+label).text('Cerrar');
			jQuery('div.'+central).animate({ width : 430 });
			jQuery('div.'+left).animate({ width : 165, opacity : 1   });
			jQuery('div.'+right).animate({ width : 175, opacity : 1   });
			jQuery.cookie('reporte', 'reporteabrir', { expires: 365 });
		}
	}
	
 	function admColumna( central, right, label )
	{
    	if(jQuery('#'+label).text() == 'Cerrar')
		{
			jQuery('#'+label).text('Abrir');
			jQuery('div.'+right).animate({ width : 0, opacity : 0 });
			jQuery('div.'+central).animate({ width : 770 });
			jQuery.cookie('columna', 'cerrar', { expires: 365 });
		}
		else
		{
			jQuery('#'+label).text('Cerrar');
			jQuery('div.'+central).animate({ width : 595 });
			jQuery('div.'+right).animate({ width : 175, opacity : 1   });
			jQuery.cookie('columna', 'abrir', { expires: 365 });
		}
	}

	function actionToggle( id )
	{
		jQuery("#"+id).slideToggle("Slow");
	}	
	
	function formToJSON( selector )
	{
		var form = {};
		jQuery(selector).find(':input[name]').each( function(){
			var self = jQuery(this);
			var name = self.attr('name');
				if (form[name]) {
					form[name] = form[name] + ',' + self.val();
				}
				else {
					form[name] = self.val();
				}
		});
		return form;
	}

	function GPTI_multiselect( iddesde , idhacia )
	{	
		jQuery('#'+iddesde+' option:selected').animate({ opacity: "hide" }, function() {
			jQuery(this).remove().appendTo('#'+idhacia).animate({ opacity: "show" }, "fast") ;
		})
	}

	function GPTI_eliminarTarea( id ) 
	{
		if( jQuery('#ntareas').val() == 1 ){
			alert('Debe existir al menos una Tarea');
		}else{
		jQuery('#tarea'+id ).animate({ height:0, opacity: "hide" }, "slow", function() { jQuery(this).remove() });
		jQuery('[name="ntareas"]').val( parseInt(jQuery('[name="ntareas"]').val()) - 1 );
		}
	}
	
	function GPTI_Req_Anexos_agregar( i ) 
	{
		var o = i + 1;
		jQuery('<div class="gpti_overflow inputfile" id="input'+o+'"><div id="cont'+o+'"><div id="mas'+o+'" ><a href="javascript:void(0);" onclick="javascript:GPTI_Req_Anexos_agregar('+o+'); return false;" title="&nbsp;+&nbsp;"><img src="templates/gpti/imagenes/mas.jpg" alt="" width="23" height="23" /></a></div></div><div><input type="file" name="REQ_ANEXOS[]" value="" size="" /></div></div>').animate({ opacity : "show" }, "slow" ).appendTo('#inputfiles');
		jQuery( 'div#mas' + i ).animate({ opacity: "hide" }, "slow").remove();
		jQuery('<div id="mas'+i+'"><a href="javascript:void(0);" onclick="javascript:GPTI_Req_Anexos_eliminar('+i+'); return false;" title="&nbsp;-&nbsp;"><img src="templates/gpti/imagenes/menos.jpg" alt="" width="23" height="23" /></a></div>').animate({ opacity: "show" }, "slow").appendTo('#cont'+i);
	}

	function GPTI_Req_Anexos_eliminar( id ) 
	{
		jQuery('#input'+id ).animate({ height:0, opacity: "hide" }, "slow", function() { jQuery(this).remove() });
	}
	
	function GPTI_Req_Ingresar()
	{
		jQuery("#REQ_AREAS_asignados").each(function(){
            jQuery("#REQ_AREAS_asignados option").attr("selected","selected");
		});
		jQuery("#REQ_VALORES_asignados").each(function(){
            jQuery("#REQ_VALORES_asignados option").attr("selected","selected");
		});
		jQuery("#REQ_MODULOS_asignados").each(function(){
            jQuery("#REQ_MODULOS_asignados option").attr("selected","selected");
		});
		
		jQuery('#gpti_msj').html('').animate({ opacity: 0});
		jQuery('[name="task"]').val('ingresar_check');
		postData = formToJSON('#frmReqIngresar');
		
		jQuery.post( 'index.php', postData, function(data)
		{
			if( parseInt(data.error) )
			{
				window.location.hash	= "top";
				jQuery('#gpti_msj').html( '<ul class="incorrecto"><li>' + data.errormsj + '</li></ul>' ).animate({  opacity: 1 });
				return false;
			}
			else
			{
				jQuery('[name="task"]').val('ingresar_submit');
				jQuery('#frmReqIngresar').submit();
			}
		}, "json");
	}
	
	function GPTI_Req_Editar()
	{
		jQuery("#REQ_AREAS_asignados").each(function(){
            jQuery("#REQ_AREAS_asignados option").attr("selected","selected");
		});
		jQuery("#REQ_VALORES_asignados").each(function(){
            jQuery("#REQ_VALORES_asignados option").attr("selected","selected");
		});
		jQuery("#REQ_MODULOS_asignados").each(function(){
            jQuery("#REQ_MODULOS_asignados option").attr("selected","selected");
		});
		
		jQuery('#gpti_msj').html('').animate({ opacity: 0});
		jQuery('[name="task"]').val('editar_check');
		postData = formToJSON('#frmReqIngresar');
		
		jQuery.post( 'index.php', postData, function(data)
		{
			if( parseInt(data.error) )
			{
				window.location.hash	= "top";
				jQuery('#gpti_msj').html( '<ul class="incorrecto"><li>' + data.errormsj + '</li></ul>' ).animate({  opacity: 1 });
				return false;
			}
			else
			{
				jQuery('[name="task"]').val('editar_submit');
				jQuery('#frmReqIngresar').submit();
			}
		}, "json");		
	}
	
	function GPTI_Reset( form )
	{
		switch( form )
		{
			case 'frmRequerimiento' : 
				jQuery('[name="REQ_PRIORIDAD"]').val('');
			break; 
			case 'frmFiltro':
				jQuery('[name="filtro_proyecto"]').val('');
				jQuery('[name="filtro_tipo"]').val('');
				jQuery('[name="filtro_prioridad"]').val('');
				jQuery('[name="filtro_gerencia"]').val('');
				jQuery('[name="filtro_estado"]').val('');
				jQuery('[name="filtro_clasificacion"]').val('');
				jQuery('[name="filtro_nombre"]').val('Nombre del proyecto');
				jQuery('[name="filtro_fecha_desde"]').val('Ingreso Desde');
				jQuery('[name="filtro_fecha_hasta"]').val('Ingreso Hasta');
				jQuery('[name="filtro_n_interno"]').val('Nº Interno');
				jQuery('[name="filtro_n_dru"]').val('Nº DRU');		
			break;			
			case 'frmTarea':
				var frm = document.frmTarea;
				for( var i=0; i<frm.elements.length; i++ )
				{
					switch( frm.elements[i].name )
					{
						case 'TAR_NOMBRE[]' :
							frm.elements[i].value = 'Nombre de la Tarea' ;						
						break;
						case 'TAR_HH_ESTIMADA[]' :
							frm.elements[i].value = 'Estimación HH' ; 
						break;
						case 'TAR_RECURSO[]' :
							frm.elements[i].selectedIndex = 0 ;
						break;
						case 'TAR_TIPO[]' :
							frm.elements[i].selectedIndex = 0 ;	
						break;
						case 'TAR_FECHA_INICIO[]' :
							frm.elements[i].value = 'Fecha Inicio';				
						break;
						case 'TAR_FECHA_TERMINO[]' :
							frm.elements[i].value = 'Fecha Termino';
						break;
						case 'TAR_OBSERVACIONES[]' :
							frm.elements[i].value = 'Observaciones';
						break;
						default:
							jQuery('[name="TAR_OBS_EJECUTOR"]').val('Observaciones dej ejecutor');
							jQuery('[name="TAR_HH_INFORMADA"]').val('HH Informadas');
							jQuery('[name="TAR_FECHA_INICIO_REAL"]').val('Fecha Inicio Real');
							jQuery('[name="TAR_FECHA_TERMINO_REAL"]').val('Fecha Termino Real');
						break;
					}
				}
			break;
			default:
				return false;
			break;
		}	
		return false;
	}
	
	function GPTI_submit( formid, tarea )
	{
		var idForm = '#'+formid ;
		switch( tarea )
		{
			case 'cambio_prioridad_solicitar' :
			case 'planificar' :
			case 'asignar' :
			case 'asignar_p	' :
			case 'ver' :
			case 'aceptar_prueba' :
			case 'pasaraprueba' :
			case 'aceptar' :
			case 'cerrar' :
					jQuery('[name="task"]').val( tarea );
			break;
			case 'ingresar_proveedor' :
					if( jQuery('#REQ_PROVEEDOR').val() != '' )
					{
						jQuery('[name="task"]').val( tarea );
						jQuery( idForm ).submit();
					}
					else
					{
						alert('Debe seleccionar un Proveedor');
						return false;
					}
			break;
			case 'editar' :
					jQuery('[name="task"]').val( 'ingresar' );
			break;
			case 'verasignar' :
					jQuery('[name="task"]').val( 'asignar' );
			break;
			case 'revisar' :
					jQuery('[name="task"]').val( 'planificar' );
			break;
			case 'buscar':
				if(jQuery('#label').text() == 'Cerrar')
				{
					jQuery('#label').text('Abrir');
					jQuery('div.gpti_derecha').animate({ width : 0, opacity : 0 });
					jQuery('div.gpti_centro_xl').animate({ width : 770 });
					jQuery.cookie('columna', 'cerrar', { expires: 365 });
				}

			jQuery( idForm ).submit();	
			break;
			case 'cambiosprioridad_submit' :
				jQuery('[name="task"]').val( tarea );
				jQuery( idForm ).submit();	
			break;
			default:
				jQuery('[name="task"]').val( tarea );
				jQuery( idForm ).submit();	
			break;
		}	
		if( formid == 'frmBuscar')
		{
			if( jQuery('[name="REQ_ID"]:checked').val() )
			{
				jQuery( idForm ).submit();	
			}
			else
			{
				alert('Debe seleccionar un Item');
				return false;
			}
		}
		else if(formid == 'frmRequerimiento')
		{
			jQuery( idForm ).submit();	
		}
		
		return false;
	}
	
	function GPTI_Buscar( tarea )
	{
		jQuery('#gpti_msj').html('');
		var errormsj = "Debe seleccionar un Requerimiento.";
		if ( undefined === jQuery("input:radio:checked").val()) 
		{
			jQuery('#gpti_msj').html( '<ul class="incorrecto"><li>' + errormsj + '</li></ul>' );
			return false;
		}
		else
		{
			jQuery('[name="task"]').val( tarea );
			jQuery('#frmFiltro').submit();
		}
	}
	
	function GPTI_Tarea_Ingresar( tarea )
	{
		if( validarTarea() )
		{
			jQuery('#gpti_msj').html('').animate({ opacity: 0});
			jQuery('[name="task"]').val('tarea_check');
			postData = formToJSON('#frmTarea');
			jQuery.post( 'index.php', postData, function(data)
			{
				if( parseInt(data.error) )
				{
					window.location.hash	= "top";
					jQuery('#gpti_msj').html( '<ul class="incorrecto"><li>' + data.errormsj + '</li></ul>' ).animate({  opacity: 1 });
					return false;
				}
				else
				{
					jQuery('[name="task"]').val('check_gerencia');
					jQuery('#frmTarea').submit();
				}
			}, "json");
		}		
	}
	
	function GPTI_Informar_Tarea()
	{
		jQuery('#gpti_msj').html('').animate({ opacity: 0});
		jQuery('[name="task"]').val('informar_check');
		postData = formToJSON('#frmTarea');
		jQuery.post( 'index.php', postData, function(data)
		{
			if( parseInt(data.error) )
			{
				window.location.hash	= "top";
				jQuery('#gpti_msj').html( '<ul class="incorrecto"><li>' + data.errormsj + '</li></ul>' ).animate({  opacity: 1 });
				return false;
			}
			else
			{
				jQuery('[name="task"]').val('informar_submit');
				jQuery('#frmTarea').submit();
			}
		}, "json");
	
	}
	
	function numbersOnly(e) { 
	  var evt = (e) ? e : window.event; 
	  var key = (evt.keyCode) ? evt.keyCode : evt.which;
	  
	  if(key != null) { 
		key = parseInt(key, 10); 

		if((key < 48 || key > 57) && (key < 96 || key > 105)) { 
		  if(!isUserFriendlyChar(key)) 
			return false; 
		} 
		else { 
		  if(evt.shiftKey) 
			return false; 
		} 
	  } 

	  return true; 
	}
	
	function isUserFriendlyChar(val) { 
	  // Backspace, Tab, Enter, Insert, and Delete 
	  //if(val == 8 || val == 9 || val == 13 || val == 45 || val == 46)
	  if(val == 8)
		return true; 


	  // Ctrl, Alt, CapsLock, Home, End, and Arrows 
	 // if((val > 16 && val < 21) || (val > 34 && val < 41)) 
		//return true; 


	  // The rest 
	  return false; 
	}