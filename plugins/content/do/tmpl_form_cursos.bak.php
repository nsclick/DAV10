<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

global $Itemid;
?>
<div class="formulario frmcursos">
<form name="DOformCursos" id="DOformCursos" method="post" action="<?php echo JRoute::_("index.php");?>">
	
	<div class="fondo margen">
        <h2>DATOS DE PARTICIPANTES</h2>
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td>
        <label for="DOformCursos_apellidoPaterno">Apellido Paterno :</label> <input type="text" name="apellidoPaterno" id="DOformCursos_apellidoPaterno" class="inputbox" size="30" /><br />
        <label for="DOformCursos_apellidoMaterno">Apellido Materno :</label> <input type="text" name="apellidoMaterno" id="DOformCursos_apellidoMaterno" class="inputbox" size="30" /><br />
        <label for="DOformCursos_nombres">Nombres :</label> <input type="text" name="nombres" id="DOformCursos_nombres" class="inputbox" size="30" /><br />
        <label for="DOformCursos_cargo">Cargo :</label> <input type="text" name="cargo" id="DOformCursos_cargo" class="inputbox" size="30" /><br />
        <label for="DOformCursos_run">RUN :</label> <input type="text" name="run" id="DOformCursos_run" class="inputbox" size="15" /><br />
        <label for="DOformCursos_fechaNacDia">Fecha de Nacimiento :</label>
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
            </select><br />
        Sexo :
            <label for="DOformCursos_sexoM">M</label><input type="radio" name="sexo" id="DOformCursos_sexoM" value="M" />
            <label for="DOformCursos_sexoF">F</label><input type="radio" name="sexo" id="DOformCursos_sexoF" value="F" /><br />
        <label for="DOformCursos_telefonos">Teléfonos :</label> <input type="text" name="telefonos" id="DOformCursos_telefonos" class="inputbox" size="30" /><br />
        <label for="DOformCursos_email">Email :</label> <input type="text" name="email" id="DOformCursos_email" class="inputbox" size="30" /><br />
        <label for="DOformCursos_curso">Curso en el que se inscribirá:</label><br />
        <input type="text" name="curso" id="DOformCursos_curso" class="inputbox" size="50" /><br />
        <label for="DOformCursos_fecha">Fecha :</label> <?php echo JHTML::_('calendar', date("Y-m-d"), 'fecha', 'DOformCursos_fecha', '%Y-%m-%d', array("class"=>"inputbox","size"=>"12"));?>
            <label for="DOformCursos_horario">Horario :</label> <input type="text" name="horario" id="DOformCursos_horario" class="inputbox" size="10" /><br />
        <label for="DOformCursos_lugar">Lugar de Realización :</label> <input type="text" name="lugar" id="DOformCursos_lugar" class="inputbox" size="30" /><br />
          </tr>
        </table>
    </div>
    
	<div class="fondo margen">
        <h2>DATOS DE LA UNIDAD</h2>
        <label for="DOformCursos_servicio">Lugar de Realización :</label> <input type="text" name="servicio" id="DOformCursos_servicio" class="inputbox" size="40" /><br />
        <label for="DOformCursos_jefatura">Jefatura Directa :</label> <input type="text" name="jefatura" id="DOformCursos_jefatura" class="inputbox" size="40" /><br />
        <label for="DOformCursos_cargo">Cargo :</label> <input type="text" name="cargo" id="DOformCursos_cargo" class="inputbox" size="20" /><br />
        <label for="DOformCursos_telefonosUnidad">Cargo :</label> <input type="text" name="telefonosUnidad" id="DOformCursos_telefonosUnidad" class="inputbox" size="30" /><br />
    </div>
    <p>INFORMACIÓN IMPORTANTE:</p>
    <p>El envío de esta ficha de inscripción no garantiza la participación en el curso, los participantes deberán confirmar su inscripción telefónicamente,  antes de realización del curso.<br />
    Para mayores detalles favor comunicarse al 7308028 , con el asistente de Capacitación y desarrollo, Sr. Luis Espinoza.</p>
    
	<input type="hidden" name="return" value="<?php echo JRoute::_("index.php?option=com_content&Itemid=$Itemid");?>" />
    <input type="hidden" name="option" value="com_do" />
    <input type="hidden" name="c" value="formularios" />
    <input type="hidden" name="plantilla" value="<?php echo $vars['form'];?>" />
    <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
</form>
</div>