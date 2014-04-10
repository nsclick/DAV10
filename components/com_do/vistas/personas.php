 <?php
/**
 * @version		$Id: personas.php 2010-07-22 sgarcia $
 * @package		Joomla
 * @subpackage	DO
 * @autor		Diseño Objetivo www.do.cl
 * @copyright	Copyright (C) 2006 - 2010 Diseño Objetivo. Todos los derechos reservados.
 * @license		LICENCIA_DO.php
 */

	/**************************************/
	/*                                    */
	/*          Diseño Objetivo           */
	/*       www.do.cl / info@do.cl       */
	/*      Fono: (56-02) 228 13 91       */
	/*                                    */
	/**************************************/
	
// sin acceso directo
defined( '_JEXEC' ) or die( 'Restricted access' );

class DoVistaPersonas
{	

	function display( &$lists )
	{
		global $Itemid;
		
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
				var frm		= document.comPersonas;
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
					alert( "Debe ingresa al menos 1 parámetro con 3 caracteres" );
					return false;
				}
				
				frm.submit();
			}
		</script>
        <div class="componente" align="left">
            <h1>Personas</h1>
            <div class="box_descripcion">
                <p>Acá usted puede buscar los datos de los funcionarios contratados de la Clínica.</p>
            </div>
            <div class="box_descripcion_bottom"><img src="<?php echo JURI::base();?>images/pix_transparente.gif" alt="" width="710" height="18" /></div>
            <!--<div class="descripcion">
                Acá usted puede buscar los datos de los funcionarios contratados de la Clínica.
            </div>-->
            <div class="margen personas_display" align="right">
              <form name="comPersonas" id="comPersonas" method="post" action="<?php echo JRoute::_("index.php?Itemid=$Itemid");?>" onkeypress="javascript:teclaPersonas(event);">
                <div class="margen"><input type="text" name="filtro_nombres" id="comPersonas_filtro_nombres" class="inputbox" value="Nombre" title="Nombre" onblur="javascript:form_texto_blur( this );" onfocus="javascript:form_texto_focus( this );" size="35" /></div>
                <div class="margen"><input type="text" name="filtro_apaterno" id="comPersonas_filtro_apaterno" class="inputbox" value="Apellido Paterno" title="Apellido Paterno" onblur="javascript:form_texto_blur( this );" onfocus="javascript:form_texto_focus( this );" size="35" /></div>
                <div class="margen"><input type="text" name="filtro_amaterno" id="comPersonas_filtro_amaterno" class="inputbox" value="Apellido Materno" title="Apellido Materno" onblur="javascript:form_texto_blur( this );" onfocus="javascript:form_texto_focus( this );" size="35" /></div>
                <div class="margen"><input type="text" name="filtro_unidad" id="comPersonas_filtro_unidad" class="inputbox" value="Unidad" title="Unidad" onblur="javascript:form_texto_blur( this );" onfocus="javascript:form_texto_focus( this );" size="35" /></div>
                <div class="margen"><input type="text" name="filtro_cargo" id="comPersonas_filtro_cargo" class="inputbox" value="Cargo" title="Cargo" onblur="javascript:form_texto_blur( this );" onfocus="javascript:form_texto_focus( this );" size="35" /></div>
                <div class="margen"><a href="javascript:void(0);" onclick="javascript:validarPersonas(); return false;" title="B&uacute;squeda Avanzada">
                	<img src="<?php echo $lists['tmpl'];?>/imagenes/mod_personas_submit.jpg" alt="B&uacute;squeda Avanzada" border="0" /></a>
                </div>
                <input type="hidden" name="option" value="com_do" />
                <input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
                <input type="hidden" name="task" value="buscar" />
              </form>
            </div>
        </div>
    	<?php
	}	

	function buscar($rows, $lists)
	{

		global $Itemid;

		if(is_array($rows) && count($rows)) { ?>
        	<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery(".modal a").hover(function(e){
						//personas-link-
						anchorid	= jQuery(this).attr('id');
						imgid		= "#personas-img-"+anchorid.substr(14);
						tdid		= "#personas-td-"+anchorid.substr(14);
						jQuery('#personas_popup_box').html( '<'+'img src="'+jQuery(imgid).attr('value')+'" alt="" border="0" /'+'>' );
						var height = jQuery('#personas_popup_box').height();
						var width = jQuery('#personas_popup_box').width();
						//pos = jQuery(this).offset();
						pos = jQuery(tdid).offset();
						leftVal=parseInt(pos['left'])-(parseInt(width)+40);
						topVal=parseInt(pos['top'])-(parseInt(height)+40);
						if (jQuery.browser.msie && jQuery.browser.version.substr(0,1) < 7) {
							//leftVal	-= 60;
							//topVal	-= 135;
						}
						jQuery('#personas_popup_box').css({left:leftVal,top:topVal}).show();
					},function(){
						jQuery('#personas_popup_box').css({left:0,top:0}).hide();
					});
					<?php if( is_array( $rows ) && count( $rows ) ) { ?>
						MM_preloadImages('<?php foreach($rows as $rr=>$row) : echo $rr ? "','":"";?><?php echo $row['FOTO'];?><?php endforeach;?>');
					<?php } ?>
				});
			</script>
	 	<?php } ?>
        <div class="componente" align="left">
            <h1>Resultado de la búsqueda</h1>
            <div class="box_descripcion">
                <p>Si sus datos no coinciden o han cambiado, por favor envie un mail con la informaci&oacute;n actualizada a comunicaciones@davila.cl, para que sean corregidos a la brevedad posible.</p>
            </div>
            <div class="box_descripcion_bottom"><img src="<?php echo JURI::base();?>images/pix_transparente.gif" alt="" width="710" height="18" /></div>
         <?php if( $lists['error'] ) { ?>
            <div class="formulario">
            	<div class="msg"><?php echo $lists['error'];?></div>
            </div>
         <?php } ?>
         <?php if(is_array($rows) && count($rows)) { ?>
            <div class="margen">
            	<table width="100%" cellpadding="2" cellspacing="0" border="0" class="com_do_personas">
                  <thead>
                    <tr>
                      <th>Nombres</th>
                      <th>Apellidos</th>
                      <th>Cargo</th>
                      <th>Unidad</th>
                      <th>E-mail</th>
                      <th>Anexo</th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                  	<?php /*
                    <tr>
                      <td valign="middle" style="border-left:none;">Sebastian</td>
                      <td valign="middle">Garcia</td>
                      <td valign="middle">Jefe IT</td>
                      <td valign="middle">IT</td>
                      <!--<td valign="middle">sebastian@do.cl</td>-->
                      <!--<td valign="middle" align="right" style="padding-right:5px;" class="modal"><a href="<?php echo $lists['tmpl'];?>/imagenes/btn_ver_foto.jpg" title="Ver Foto"><img src="<?php echo $lists['tmpl'];?>/imagenes/btn_ver_foto.jpg" alt="Ver Foto" border="0" align="right" /></a></td>-->
                      <td valign="middle" align="right" style="padding-right:5px;" class="modal">
                      	<a href="<?php echo $lists['tmpl'];?>/imagenes/btn_ver_foto.jpg" id="personas-link-1" title="Ver Foto"><img src="<?php echo $lists['tmpl'];?>/imagenes/btn_ver_foto.jpg" alt="Ver Foto" border="0" align="right" /></a>
                      	<input type="hidden" name="personas-img-1" id="personas-img-1" value="<?php echo $lists['tmpl'];?>/imagenes/reconocimiento1.jpg" />
                      </td>
                    </tr>
                    */ ?>
                  <?php foreach( $rows as $row ) { ?>
                    <tr>
                      <td valign="middle" style="border-left:none;"><?php echo $row['NOMBRES'];?></td>
                      <td valign="middle"><?php echo $row['APELLIDOS'];?></td>
                      <td valign="middle"><?php echo $row['CARGO'] ? $row['CARGO'] : '&nbsp;';?></td>
                      <td valign="middle"><?php echo $row['UNIDAD'] ? $row['UNIDAD'] : '&nbsp;';?></td>
                      <td valign="middle"><?php echo $row['EMAIL'] ? $row['EMAIL'] : '&nbsp;';?></td>
                      <td valign="middle"><?php echo $row['ANEXO'] ? $row['ANEXO'] : '&nbsp;';?></td>
                      <td valign="middle" align="right" style="padding-right:5px;" class="modal" id="personas-td-<?php echo $row['RUT_FUNCIONARIO'];?>">
                      	<a href="<?php echo $lists['tmpl'];?>/imagenes/btn_ver_foto.jpg" id="personas-link-<?php echo $row['RUT_FUNCIONARIO'];?>" title="Ver Foto">
                      		<img src="<?php echo $lists['tmpl'];?>/imagenes/btn_ver_foto.jpg" alt="Ver Foto" border="0" align="right" />
                      	</a>
                        <input type="hidden" name="personas-img-<?php echo $row['RUT_FUNCIONARIO'];?>" id="personas-img-<?php echo $row['RUT_FUNCIONARIO'];?>" value="<?php echo $row['FOTO'];?>" />
                      </td>
                    </tr>
                  <?php } ?>
                  </tbody>
                </table>
            </div>
          <?php } else { ?>
            <div class="formulario">
            	<div class="msg">Su consulta, no obtuvo resultados</div>
           	</div>
          <?php } ?>
        </div>
        <?php
	}
}

?>