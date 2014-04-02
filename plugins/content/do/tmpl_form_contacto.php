<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

global $Itemid;
?>
<script type="text/javascript">
	function _DOformContacto(frmContacto)
	{
		alerta	= "";
		error	= "";
		
		if( frmContacto.comentarios.value == '' ){ alerta += "\n - Comentarios"; }
		if( frmContacto.nombre.value == '' ){ alerta += "\n - Nombre"; }
		if( frmContacto.apellidos.value == '' ){ alerta += "\n - Apellidos"; }
		if( frmContacto.rut.value == '' ){ alerta += "\n - Rut"; }
		if( frmContacto.direccion.value == '' ){ alerta += "\n - Dirección"; }
		if( frmContacto.telefono.value == '' ){ alerta += "\n - Teléfono"; }
		if( frmContacto.email.value == '' ){ alerta += "\n - E-mail"; }
		if( frmContacto.contactar[0].checked == false && frmContacto.contactar[1].checked && frmContacto.contactar[2].checked ){ alerta += "\n - Cómo quiere ser contactado"; }
		
		if( frmContacto.rut.value != '' && !Rut(frmContacto.rut.value, frmContacto.rut) ){ error += "\n - Rut incorrecto"; }
		if( frmContacto.email.value != '' && !RevisarEmail(frmContacto.email.value) ){ error += "\n - E-mail incorrecto"; }
		//Rut(texto,objrut)
		//RevisarEmail(mail)
		
		if( alerta != "" ){ alert("Debe completar los siguientes campos"+alerta); return false; }
		if( error != "" ){ alert("Debe revisar los siguientes errores:"+error); return false; }
		
		return true;
		//frmContacto.submit();
	}
</script>
<div class="formulario frmcontacto" align="left">
<form name="DOformContacto" id="DOformContacto" method="post" action="<?php echo JRoute::_("index.php");?>" onsubmit="javascript:return _DOformContacto(this);">
<?php if( $msg != '' ): ?>
	<div class="msg"><?php echo utf8_encode($msg); ?></div>
<?php endif; ?>
	<div class="caja1 texto1"><label for="DOformContacto_comentarios">Por favor ingresa tus comentarios</label></div>
    <div class="caja1"><textarea name="comentarios" id="DOformContacto_comentarios" class="inputbox_area" rows="4" cols="" style="width:99%;"></textarea></div>
	<div class="fondo">
   		<table width="100%" align="center" cellpadding="0" cellspacing="5" border="0" class="frmcontacto">
          <tr>
            <td valign="top"><label for="DOformContacto_nombre">NOMBRE</label><br /><input type="text" name="nombre" id="DOformContacto_nombre" class="inputbox_text1" size="50" /></td>
            <td valign="top"><label for="DOformContacto_apellidos">APELLIDOS</label><br /><input type="text" name="apellidos" id="DOformContacto_apellidos" class="inputbox_text1" size="50" /></td>
          </tr>
          <tr>
            <td valign="top"><label for="DOformContacto_rut">RUT</label><br /><input type="text" name="rut" id="DOformContacto_rut" class="inputbox_text1" size="50" /></td>
            <td valign="top"><label for="DOformContacto_direccion">DIRECCIÓN</label><br /><input type="text" name="direccion" id="DOformContacto_direccion" class="inputbox_text1" size="50" /></td>
          </tr>
          <tr>
            <td valign="top"><label for="DOformContacto_telefono">TELÉFONO</label><br /><input type="text" name="telefono" id="DOformContacto_telefono" class="inputbox_text1" size="50" /></td>
            <td valign="top"><label for="DOformContacto_email">E-MAIL</label><br /><input type="text" name="email" id="DOformContacto_email" class="inputbox_text1" size="50" /></td>
          </tr>
          <tr>
            <td colspan="2">CÓMO QUIERE SER CONTACTADO</td>
          </tr>
          <tr>
          	<td colspan="2">
            	<div class="radio" style="float:left; width:110px; background-color:#ABDBE6;"><input type="radio" name="contactar" id="DOformContacto_contactar1" value="Teléfono" /><label for="DOformContacto_contactar1">Teléfono</label></div>
            	<div class="radio" style="float:left; width:110px; background-color:#ABDBE6; margin-left:60px;"><input type="radio" name="contactar" id="DOformContacto_contactar2" value="E-mail" /><label for="DOformContacto_contactar2">E-mail</label></div>
            	<!--<div class="radio" style="float:left; width:110px; background-color:#ABDBE6; margin-left:60px;"><input type="radio" name="contactar" id="DOformContacto_contactar3" value="Correo" /><label for="DOformContacto_contactar3">Correo</label></div>-->
            </td>
          </tr>
          <tr>
            <td align="right" colspan="2"><input type="submit" name="btnsubmit" id="DOformContacto_submit" class="button_1" value="ENVIAR" /></td>
          </tr>
        </table>
    </div>
	<input type="hidden" name="return" value="<?php echo JRoute::_("index.php?option=com_content&Itemid=$Itemid");?>" />
    <input type="hidden" name="option" value="com_do" />
    <input type="hidden" name="c" value="formularios" />
    <input type="hidden" name="plantilla" value="<?php echo $vars['form'];?>" />
    <input type="hidden" name="recipientes" value="<?php echo base64_encode($vars['email']);?>" />
    <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
</form>
</div>