<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

global $Itemid;
if( $msg == "fondos" ):
	$msg = 	"Estimado concursante:<br /><br />".
			"El formulario ha sido completado correctamente.<br />".
			"Se enviar&aacute; un certificado  de postulaci&oacute;n, al correo electr&oacute;nico que registr&oacute;, el cual debe presentarlo en la Embajada o Consulado de Chile correspondiente, para continuar con el proceso.<br /><br />".
			"Ante cualquier duda comunicarse a <a href=\"mailto:fondoconcursabledicoex@minrel.gov.cl\" class=\"detalle_det\">fondoconcursabledicoex@minrel.gov.cl</a><br /><br />".
			"Direcci&oacute;n para la Comunidades Chilenas en el Exterior"
			;
endif;
?>
						<?php if( $msg ) : ?>
                        	<div class="mensajes" align="center" style="padding:10px;"><?php echo $msg;?></div>
                        <?php else : ?>
                            <form name="fondosConcursables" id="fondosConcursables" method="post" action="index.php" onsubmit="return formFondosValidar()" enctype="multipart/form-data">
                              <table width="98%" border="0" cellspacing="0" cellpadding="0" class="tabla">
                                <tr>
                                  <td align="left" valign="middle"><strong>FORMULARIO &Uacute;NICO DE PRESENTACI&Oacute;N DE PROYECTOS</strong></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>


                                <tr>
                                  <td align="left" valign="middle"><strong>DATOS POSTULANTE:</strong></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>Asociaci&oacute;n y/o agrupaci&oacute;n ejecutora:</strong></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><input name="asociacion" type="text" size="50" class="inputbox" /></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>Pa&iacute;s:</strong></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><input name="pais" type="text" size="25" class="inputbox" /></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>Ciudad:</strong></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><input name="ciudad" type="text" size="25" class="inputbox" /></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>N&ordm; Personalidad Jur&iacute;dica:</strong></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><input name="juridica" type="text" size="30" class="inputbox" /></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;&nbsp;A&ntilde;o obtenci&oacute;n: <input name="obtencion" type="text" size="5" class="inputbox" /></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>Nombre del representante legal:</strong></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><input name="representante" type="text" size="50" class="inputbox" /></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>Correo Electr&oacute;nico:</strong></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><input name="email" type="text" size="30" class="inputbox" /></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>Tel&eacute;fono:</strong></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><input name="telefono" type="text" size="50" class="inputbox" /></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>Domicilio:</strong></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><input name="domicilio" type="text" size="30" class="inputbox" /></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>PROYECTO:</strong></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>L&iacute;nea de postulaci&oacute;n:</strong></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><select name="lineapostulacion" class="inputbox">
                                  	<option value="" selected="selected">- Seleccionar -</option>
                                    <option value="Actividades Asociativas y de Vinculaci&oacute;n con Chile">Actividades Asociativas y de Vinculaci&oacute;n con Chile</option>
                                    <option value="Actividades Deportivas y de Recreaci&oacute;n">Actividades Deportivas y de Recreaci&oacute;n</option>
                                    <option value="Actividades Culturales">Actividades Culturales</option>
                                  </select></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>T&iacute;tulo del Proyecto:</strong></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><input name="titulo" type="text" size="50" class="inputbox" /></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabla">
                                      <tr>
                                        <td width="39%" align="right" valign="top">Fecha de Inicio</td>
                                        <td width="1%" align="center" valign="top">:</td>
                                        <td width="60%" align="left" valign="top"><input name="fechaInicioActividad" type="text" value="dd/mm/aaaa" size="12" class="inputbox" /></td>
                                      </tr>
                                      <tr>
                                        <td align="right" valign="top">Fecha de T&eacute;rmino</td>
                                        <td align="center" valign="top">:</td>
                                        <td align="left" valign="top"><label>
                                        <input name="fechaTerminoActividad" type="text" value="dd/mm/aaaa" size="12" class="inputbox" />
                                        </label></td>
                                      </tr>
                                    </table></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>Grupo objetivo:</strong> <?php echo JHTML::_('tooltip', JText::_( 'A quiénes está dirigido el proyecto.' ) ); ?></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><input name="grupo" type="text" size="50" class="inputbox" /></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>Resumen del Proyecto:</strong></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><textarea name="resumen" cols="50" rows="6" class="inputbox">En uno o dos p&aacute;rrafos sintetizar  los objetivos y contenidos del proyecto.</textarea></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>1.0	Objetivo general:</strong></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><textarea name="objetivo" cols="50" rows="6" class="inputbox">Completar</textarea></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>2.0	Antecedentes y  justificaci&oacute;n:</strong></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><textarea name="antecedentes" cols="50" rows="6" class="inputbox">Completar</textarea></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>2.1	Descripci&oacute;n:</strong> <?php echo JHTML::_('tooltip', JText::_( 'Aquí debe explicar qué se hará en el proyecto.' ) ); ?></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><textarea name="descripcion" cols="50" rows="6" class="inputbox">Completar</textarea></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>2.2	Metodolog&iacute;a de trabajo:</strong> <?php echo JHTML::_('tooltip', JText::_( 'Aquí debe explicar cómo se realizará el proyecto.' ) ); ?></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><textarea name="metodologia" cols="50" rows="6" class="inputbox">Completar</textarea></td>
                                </tr>
                                <tr> 
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>3.0	Prop&oacute;sito:</strong> <?php echo JHTML::_('tooltip', JText::_( 'Aquí debe explicar qué busca al realizar este proyecto.' ) ); ?></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><textarea name="propositos" cols="50" rows="6" class="inputbox">Completar</textarea></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>4.0	Resultados esperados:</strong></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><textarea name="resultados" cols="50" rows="6" class="inputbox">Completar</textarea></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><a name="gantt">&nbsp;</a></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>6.0	Carta Gantt:</strong> <?php echo JHTML::_('tooltip', JText::_( 'Ingrese las actividades que se realizarán en el proyecto.' ) ); ?></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><table border="0" cellspacing="0" cellpadding="0" id="tablaGantt" class="tabla">
                                    <tr>
                                      <td width="25" align="center" valign="top">&nbsp;</td>
                                      <td align="left" valign="bottom"><strong>Actividades</strong></td>
                                      <td align="center" valign="bottom"><strong>Fecha Inicio</strong></td>
                                      <td align="center" valign="bottom"><strong>Fecha T&eacute;rmino</strong></td>
                                      <td align="center" valign="bottom"><strong>Encargado</strong></td>
                                    </tr>
                                    <tr>
                                      <td align="center" valign="top">
                                        <input type="checkbox" name="checkactividad1" id="checkactividad1" class="inputbox"/>                                      </td>
                                      <td align="left" valign="top"><input name="actividad1" id="actividad1" type="text" value="Actividad" size="18" class="inputbox" /></td>
                                      <td align="center" valign="top"><input name="ganttFechaInicio1" id="ganttFechaInicio1" type="text" value="dd/mm/aaaa" size="8" class="inputbox" /></td>
                                      <td align="center" valign="top"><input name="ganttFechaTermino1" id="ganttFechaTermino1" type="text" value="dd/mm/aaaa" size="8" class="inputbox" /></td>
                                      <td align="center" valign="top"><input name="ganttEncargado1" id="ganttEncargado1" type="text" value="Encargado Actividad" size="10" class="inputbox" /></td>
                                    </tr>
                                  </table></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><a href="#gantt" onclick="javascript:formFondosActividades('-')" onmouseover="return overlib('Elimina la(s) actividad(es) seleccionada(s)', CAPTION, 'Quitar Actividad', BELOW, RIGHT);" onmouseout="return nd();">[-]</a> Actividad <a href="#gantt" onclick="javascript:formFondosActividades('+')" onmouseover="return overlib('Agrega una nueva actividad', CAPTION, 'Agregar Actividad', BELOW, RIGHT);" onmouseout="return nd();">[+]</a></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>7.0	Presupuesto COSTO:</strong></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><table border="0" cellspacing="0" cellpadding="0" id="tablaCostos" class="tabla">
                                    <tr>
                                      <td width="25" align="center" valign="top">&nbsp;</td>
                                      <td align="left" valign="bottom"><strong>Actividades</strong></td>
                                      <td align="center" valign="bottom"><strong>Costo Total</strong></td>
                                      <td align="center" valign="bottom"><strong>Aporte Local</strong></td>
                                      <td align="center" valign="bottom"><strong>Aporte Solicitado</strong></td>
                                    </tr>
                                    <tr>
                                      <td align="center" valign="top"><label></label></td>
                                      <td align="left" valign="top"><input name="actividadClon1" id="actividadClon1" type="text" value="Actividad" size="18" class="inputbox" disabled="disabled" /></td>
                                      <td align="center" valign="top"><input name="costo1" id="costo1" type="text" value="USD" size="8" class="inputbox"/></td>
                                      <td align="center" valign="top"><input name="aporte1" id="aporte1" type="text" value="USD" size="8" class="inputbox"/></td>
                                      <td align="center" valign="top"><input name="solicitado1" id="solicitado1" type="text" value="USD" size="8" class="inputbox"/></td>
                                    </tr>
                                    <tr>
                                      <td align="center" valign="top">&nbsp;</td>
                                      <td align="right" valign="top">Totales:</td>
                                      <td align="center" valign="top"><input name="costoTotal" type="text" value="USD" size="8" class="inputbox" disabled="disabled" /></td>
                                      <td align="center" valign="top"><input name="localTotal" type="text" value="USD" size="8" class="inputbox" disabled="disabled" /></td>
                                      <td align="center" valign="top"><input name="aporteSolicitado" type="text" value="USD" size="8" class="inputbox" disabled="disabled" /></td>
                                    </tr>
                                  </table></td>
                                </tr>
                                
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><a href="#gantt" onclick="javascript:formFondosCalcular()" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('fondos_bot_calcular','','<?php echo JURI::base();?>templates/dicoex/images/spanish/bot_calcular_on.jpg',1)"><img src="<?php echo JURI::base();?>templates/dicoex/images/spanish/bot_calcular_off.jpg" alt="Calcular Costos" name="fondos_bot_calcular" width="82" height="23" hspace="10" vspace="10" border="0" id="fondos_bot_calcular" /></a></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>TOTAL DEL PROYECTO: </strong><span id="spTotalProyecto"></span><input type="hidden" name="hdTotalProyecto" value="" /></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>APORTE LOCAL: </strong><span id="spAporteLocal"></span><input type="hidden" name="hdAporteLocal" value="" /></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>APORTE SOLICITADO: </strong><span id="spAporteSolicitado"></span><input type="hidden" name="hdAporteSolicitado" value="" /></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><strong>Anexos:</strong></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><input name="anexos" type="file" size="50" class="inputbox" /></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" id="contenedorCatchap">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left" valign="middle"><input type="button" name="resetCaptcha" value="Cambiar Imagen Verificadora" onclick="javascript:plugin_do_captcha()" /></td>
                                </tr>
                                <tr>
                                  <td><a href="#gantt" onclick="javascript:if(formFondosValidar()){ document.fondosConcursables.submit() }" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('fondos_bot_enviar','','<?php echo JURI::base();?>templates/dicoex/images/spanish/bot_enviar_on.jpg',1)"><img src="<?php echo JURI::base();?>templates/dicoex/images/spanish/bot_enviar_off.jpg" alt="Enviar Formulario" name="fondos_bot_enviar" width="82" height="23" hspace="10" vspace="10" border="0" id="fondos_bot_enviar" /></a></td>
                                </tr>
                              </table>
                            <input type="hidden" name="option" value="com_do" />
                            <input type="hidden" name="c" value="formularios" />
                            <input type="hidden" name="plantilla" value="proyecto" />
                            <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
                          </form>
                          <script type="text/javascript">
						  	plugin_do_captcha();
						  </script>
                        <?php endif;?>
