<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

global $Itemid;
?>
<script type="text/javascript">
	function _DOformCursos(frmCursos)
	{
		alerta	= "";
		error	= "";
		
		if( frmCursos.apellidoPaterno.value == '' ){ alerta += "\n - Apellido Paterno"; }
		if( frmCursos.apellidoMaterno.value == '' ){ alerta += "\n - Apellido Materno"; }
		if( frmCursos.nombres.value == '' ){ alerta += "\n - Nombres"; }
		if( frmCursos.cargo.value == '' ){ alerta += "\n - Cargo"; }
		if( frmCursos.run.value == '' ){ alerta += "\n - RUN"; }
		if( frmCursos.sexo[0].checked == false && frmCursos.sexo[1].checked == false ){ alerta += "\n - Sexo"; }
		if( frmCursos.telefonos.value == '' ){ alerta += "\n - Teléfonos"; }
		if( frmCursos.email.value == '' ){ alerta += "\n - E-mail"; }
		if( frmCursos.curso.value == '' ){ alerta += "\n - Curso en el que se inscribirá"; }
		if( frmCursos.fecha.value == '' ){ alerta += "\n - Fecha"; }
		if( frmCursos.horario.value == '' ){ alerta += "\n - Horario"; }
		if( frmCursos.lugar.value == '' ){ alerta += "\n - Lugar de Realización"; }
		if( frmCursos.servicio.value == '' ){ alerta += "\n - Servicio"; }
		if( frmCursos.jefatura.value == '' ){ alerta += "\n - Jefatura Directa"; }
		if( frmCursos.cargoUnidad.value == '' ){ alerta += "\n - Cargo de la Unidad"; }
		if( frmCursos.telefonosUnidad.value == '' ){ alerta += "\n - Teléfonos de la unidad"; }
		
		
		if( frmCursos.run.value != '' && !Rut(frmCursos.run.value, frmCursos.rut) ){ error += "\n - RUN incorrecto"; }
		if( frmCursos.email.value != '' && !RevisarEmail(frmCursos.email.value) ){ error += "\n - E-mail incorrecto"; }
		//Rut(texto,objrut)
		//RevisarEmail(mail)
		
		if( alerta != "" ){ alert("Debe completar los siguientes campos"+alerta); return false; }
		if( error != "" ){ alert("Debe revisar los siguientes errores:"+error); return false; }
		
		return true;
		//frmContacto.submit();
	}
</script>
<div class="formulario frmcursos" align="left">
<form name="DOformCursos" id="DOformCursos" method="post" action="<?php echo JRoute::_("index.php");?>" onsubmit="javascript:return _DOformCursos(this);">
<?php if( $msg != '' ): ?>
	<div class="msg"><?php echo utf8_encode($msg); ?></div>
<?php endif; ?>
	<div class="fondo margen">
        <h2>DATOS DE PARTICIPANTE</h2>
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="25%"><label for="DOformCursos_apellidoPaterno">Apellido Paterno :</label></td>
            <td><input type="text" name="apellidoPaterno" id="DOformCursos_apellidoPaterno" class="inputbox" size="30" /></td>
          </tr>
          <tr>
            <td><label for="DOformCursos_apellidoMaterno">Apellido Materno :</label></td>
            <td><input type="text" name="apellidoMaterno" id="DOformCursos_apellidoMaterno" class="inputbox" size="30" /></td>
          </tr>
          <tr>
            <td><label for="DOformCursos_nombres">Nombres :</label></td>
            <td><input type="text" name="nombres" id="DOformCursos_nombres" class="inputbox" size="30" /></td>
          </tr>
          <tr>
            <td><label for="DOformCursos_cargo">Cargo :</label></td>
            <td><input type="text" name="cargo" id="DOformCursos_cargo" class="inputbox" size="30" /></td>
          </tr>
          <tr>
            <td><label for="DOformCursos_run">RUN :</label></td>
            <td><input type="text" name="run" id="DOformCursos_run" class="inputbox" size="15" /></td>
          </tr>
          <tr>
            <td><label for="DOformCursos_fechaNacDia">Fecha de Nacimiento :</label></td>
            <td>
                <select name="fechaNacDia" id="DOformCursos_fechaNacDia" class="inputbox">
                <?php for( $d=1; $d<=31; ++$d ): ?>
                    <option value="<?php echo $d<10?'0'.$d:$d;?>"><?php echo $d<10?'0'.$d:$d;?></option>
                <?php endfor; ?>
                </select>
                <select name="fechaNacMes" id="DOformCursos_fechaNacMes" class="inputbox">
                    <option value="Enero">Enero</option>
                    <option value="Febrero">Febrero</option>
                    <option value="Marzo">Marzo</option>
                    <option value="Abril">Abril</option>
                    <option value="Mayo">Mayo</option>
                    <option value="Junio">Junio</option>
                    <option value="Julio">Julio</option>
                    <option value="Agosto">Agosto</option>
                    <option value="Septiembre">Septiembre</option>
                    <option value="Octubre">Octubre</option>
                    <option value="Noviembre">Noviembre</option>
                    <option value="Diciembre">Diciembre</option>
                </select>
                <select name="fechaNacAnno" id="DOformCursos_fechaNacAnno" class="inputbox">
                <?php for( $a=1900; $a<=2050; ++$a ): ?>
                    <option value="<?php echo $a;?>"><?php echo $a;?></option>
                <?php endfor; ?>
                </select>
            </td>
		  </tr>
          <tr>
            <td>Sexo :</td>
            <td>
                <label for="DOformCursos_sexoM">M</label><input type="radio" name="sexo" id="DOformCursos_sexoM" value="M" />
                <label for="DOformCursos_sexoF">F</label><input type="radio" name="sexo" id="DOformCursos_sexoF" value="F" />
            </td>
          </tr>
          <tr>
            <td><label for="DOformCursos_telefonos">Teléfonos :</label></td>
            <td><input type="text" name="telefonos" id="DOformCursos_telefonos" class="inputbox" size="30" /></td>
          </tr>
          <tr>
            <td><label for="DOformCursos_email">Email :</label></td>
            <td><input type="text" name="email" id="DOformCursos_email" class="inputbox" size="30" /></td>
          </tr>
          <tr>
            <td><label for="DOformCursos_curso">Curso en el que se inscribirá:</label></td>
            <td><input type="text" name="curso" id="DOformCursos_curso" class="inputbox" size="50" /></td>
          </tr>
          <tr>
            <td><label for="DOformCursos_fecha">Fecha :</label></td>
			<td><?php echo JHTML::_('calendar', date("Y-m-d"), 'fecha', 'DOformCursos_fecha', '%Y-%m-%d', array("class"=>"inputbox","size"=>"12"));?></td>
          </tr>
          <tr>
            <td><label for="DOformCursos_horario">Horario :</label></td>
            <td><input type="text" name="horario" id="DOformCursos_horario" class="inputbox" size="10" /></td>
          </tr>
          <tr>
            <td><label for="DOformCursos_lugar">Lugar de Realización :</label></td>
            <td><input type="text" name="lugar" id="DOformCursos_lugar" class="inputbox" size="30" /></td>
          </tr>
        </table>
    </div>
    
	<div class="fondo margen">
        <h2>DATOS DE LA UNIDAD</h2>
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="25%"><label for="DOformCursos_servicio">Servicio :</label></td>
            <td><input type="text" name="servicio" id="DOformCursos_servicio" class="inputbox" size="40" /></td>
          </tr>
          <tr>
        	<td><label for="DOformCursos_jefatura">Jefatura Directa :</label></td>
            <td><input type="text" name="jefatura" id="DOformCursos_jefatura" class="inputbox" size="40" /></td>
          </tr>
          <tr>
        	<td><label for="DOformCursos_cargoUnidad">Cargo :</label></td>
            <td><input type="text" name="cargoUnidad" id="DOformCursos_cargoUnidad" class="inputbox" size="20" /></td>
          </tr>
          <tr>
        	<td><label for="DOformCursos_telefonosUnidad">Teléfonos de la unidad :</label></td>
            <td><input type="text" name="telefonosUnidad" id="DOformCursos_telefonosUnidad" class="inputbox" size="30" /></td>
          </tr>
    	</table>
    </div>
    <p><strong>INFORMACIÓN IMPORTANTE:</strong></p>
    <p>El envío de esta ficha de inscripción no garantiza la participación en el curso, los participantes deberán confirmar su inscripción telefónicamente,  antes de realización del curso.<br />
    Para mayores detalles favor comunicarse al 7308028 , con el asistente de Capacitación y desarrollo, Sr. Luis Espinoza.</p>
    <div align="right"><input type="submit" name="btnsubmit" id="DOformCursos_submit" class="button_1" value="ENVIAR" /></div>
	<input type="hidden" name="return" value="<?php echo JRoute::_("index.php?option=com_content&Itemid=$Itemid");?>" />
    <input type="hidden" name="option" value="com_do" />
    <input type="hidden" name="c" value="formularios" />
    <input type="hidden" name="plantilla" value="<?php echo $vars['form'];?>" />
    <input type="hidden" name="recipientes" value="<?php echo base64_encode($vars['email']);?>" />
    <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
</form>
</div>