<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
		$app					=& JFactory::getApplication();
		$template				= JURI::base() . 'templates/' . $app->getTemplate();
?>
<script type="text/javascript">
	function teclaPersonas(e)
	{
		tcl	= (document.all) ? e.keyCode : e.which;
		if (tcl==13){
			validarPersonas();
		}
	}
	
	function validarPersonas()
	{
		var frm		= document.frmModPersonas;
		var params	= false;
		
		nombres		= String(frm.filtro_nombres.value);
		if( nombres != "" && nombres != frm.filtro_nombres.title && nombres.length > 2  )
		{
			params	= true;
			str		= String(frm.filtro_nombres.value);
			str		= str.toUpperCase();
			str		= str.replace(String.fromCharCode(193),"A");
			str		= str.replace(String.fromCharCode(201),"E");
			str		= str.replace(String.fromCharCode(205),"I");
			str		= str.replace(String.fromCharCode(211),"O");
			str		= str.replace(String.fromCharCode(218),"U");
			frm.filtro_nombres.value	= str;
		}
				
		apaterno	= String(frm.filtro_apaterno.value);
		if( apaterno != "" && apaterno != frm.filtro_apaterno.title && apaterno.length > 2  )
		{
			params	= true;
			str		= String(frm.filtro_apaterno.value);
			str		= str.toUpperCase();
			str		= str.replace(String.fromCharCode(193),"A");
			str		= str.replace(String.fromCharCode(201),"E");
			str		= str.replace(String.fromCharCode(205),"I");
			str		= str.replace(String.fromCharCode(211),"O");
			str		= str.replace(String.fromCharCode(218),"U");
			frm.filtro_apaterno.value	= str;
		}
		
		amaterno	= String(frm.filtro_amaterno.value);
		if( amaterno != "" && amaterno != frm.filtro_amaterno.title && amaterno.length > 2  )
		{
			params	= true;
			str		= String(frm.filtro_amaterno.value);
			str		= str.toUpperCase();
			str		= str.replace(String.fromCharCode(193),"A");
			str		= str.replace(String.fromCharCode(201),"E");
			str		= str.replace(String.fromCharCode(205),"I");
			str		= str.replace(String.fromCharCode(211),"O");
			str		= str.replace(String.fromCharCode(218),"U");
			frm.filtro_amaterno.value	= str;
		}
				
		unidad		= String(frm.filtro_unidad.value);
		if( unidad != "" && unidad != frm.filtro_unidad.title && unidad.length > 2  )
		{
			params	= true;
			str		= String(frm.filtro_unidad.value);
			str		= str.toUpperCase();
			str		= str.replace(String.fromCharCode(193),"A");
			str		= str.replace(String.fromCharCode(201),"E");
			str		= str.replace(String.fromCharCode(205),"I");
			str		= str.replace(String.fromCharCode(211),"O");
			str		= str.replace(String.fromCharCode(218),"U");
			frm.filtro_unidad.value		= str;
		}
		
		cargo		= String(frm.filtro_cargo.value);
		if( cargo != "" && cargo != frm.filtro_cargo.title && cargo.length > 2  )
		{
			params	= true;
			str		= String(frm.filtro_cargo.value);
			str		= str.toUpperCase();
			str		= str.replace(String.fromCharCode(193),"A");
			str		= str.replace(String.fromCharCode(201),"E");
			str		= str.replace(String.fromCharCode(205),"I");
			str		= str.replace(String.fromCharCode(211),"O");
			str		= str.replace(String.fromCharCode(218),"U");
			frm.filtro_cargo.value		= str;
		}
		
		if( !params )
		{
			alert( "Debe ingresa al menos 1 parametro con 3 caracteres" );
			return false;
		}
		
		frm.submit();
	}
</script>
<div class="mod_boxshome mod_personas" align="left" style="width:314px;">
	<div style="height:40px;">
	<h2><?php echo $datos->subtitulo;?></h2>
	<h1><?php echo $datos->titulo;?></h1>
    </div>
    <div class="form" align="right">
      <form name="frmModPersonas" id="frmModPersonas" method="post" action="<?php echo JRoute::_("index.php?Itemid=$datos->menu");?>" onkeypress="javascript:teclaPersonas(event);">
      	<div class="sep"><input type="text" name="filtro_nombres" id="modPersonas_filtro_nombres" class="inputbox" value="Nombre" title="Nombre" onblur="javascript:form_texto_blur( this );" onfocus="javascript:form_texto_focus( this );" size="35" /></div>
    	<div class="sep"><input type="text" name="filtro_apaterno" id="modPersonas_filtro_apaterno" class="inputbox" value="Apellido Paterno" title="Apellido Paterno" onblur="javascript:form_texto_blur( this );" onfocus="javascript:form_texto_focus( this );" size="35" /></div>
        <div class="sep"><input type="text" name="filtro_amaterno" id="modPersonas_filtro_amaterno" class="inputbox" value="Apellido Materno" title="Apellido Materno" onblur="javascript:form_texto_blur( this );" onfocus="javascript:form_texto_focus( this );" size="35" /></div>
    	<div class="sep"><input type="text" name="filtro_unidad" id="modPersonas_filtro_unidad" class="inputbox" value="Unidad" title="Unidad" onblur="javascript:form_texto_blur( this );" onfocus="javascript:form_texto_focus( this );" size="35" /></div>
    	<div class="sep"><input type="text" name="filtro_cargo" id="modPersonas_filtro_cargo" class="inputbox" value="Cargo" title="Cargo" onblur="javascript:form_texto_blur( this );" onfocus="javascript:form_texto_focus( this );" size="35" /></div>
        <div class="sep"><a href="javascript:void(0);" onclick="javascript:validarPersonas(); return false;" title="B&uacute;squeda Avanzada"><img src="<?php echo $template;?>/imagenes/mod_personas_submit.jpg" alt="B&uacute;squeda Avanzada" border="0" /></a></div>
        <input type="hidden" name="option" value="com_do" />
        <input type="hidden" name="Itemid" value="<?php echo $datos->menu;?>" />
        <input type="hidden" name="task" value="buscar" />
      </form>
    </div>
</div>