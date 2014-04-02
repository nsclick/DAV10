<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

global $Itemid;

?>
                        <script type="text/javascript">
							function validacion(objform){
								var alerta="";
								var error="";

								if(objform.nombre.value==""){ alerta+="- Nombre\n"; }
								if(objform.pais.selectedIndex==0){ alerta+="- Pais\n"; }
								if(objform.email.value==""){ alerta+="- Email\n";
								}else{
									
								}
								if(objform.comentarios.value==""){ alerta+="- Comentarios\n"; }
								
								if(alerta!=""){
									alert("Debe completar los siguientes campos:\n"+alerta);
									return false;
								}else if(error!=""){
									alert("Debe revisar los siguientes errores:\n"+error);
									return false;
								}
								return true;
							}
						</script>
						<?php if( $msg ) : ?>
                        	<div class="mensajes" align="center" style="padding:10px;"><?php echo $msg;?></div>
                        <?php endif;?>
                            <form name="formExperiencia" id="formExperiencia" method="post" action="index.php" onsubmit="javascript:return validacion(this)" enctype="multipart/form-data">
                                <table class="detalle_det" width="100%">
                                  <tr>
                                    <td width="100" align="right"><?php echo _FORM_NOMBRE; ?></td>
                                    <td width="5">:</td>
                                    <td width="" align="left"><input class="inputbox" type="text" name="nombre" value="" size="30" /></td>
                                  </tr>
                                  <tr>
                                    <td align="right"><?php echo _FORM_PAIS; ?></td>
                                    <td>:</td>
                                    <td align="left">
                                        <select name="pais" class="inputbox" size="1">
                                            <option value="0" selected="selected">- Seleccione una opci&oacute;n -</option>
                                        <?php
                                          foreach( $paises as $item ){
                                            if( is_object( $item ) ){
                                        ?>	
                                            <option value="<?php echo $item->nombre; ?>"><?php echo $item->nombre; ?></option>
                                        <?php
                                            }else{ echo $item; }
                                          }
                                        ?>	
                                        </select>
        
                                    </td>
                                  </tr>
                                  <tr>
                                    <td align="right"><?php echo _FORM_EMAIL; ?></td>
                                    <td>:</td>
                                    <td align="left"><input class="inputbox" type="text" name="email" value="" size="30" /></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="top"><?php echo _FORM_COMENTARIOS; ?></td>
                                    <td valign="top">:</td>
                                    <td align="left" valign="top"><textarea name="comentarios" class="inputbox" cols="30" rows="6"></textarea></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="top"><?php echo _FORM_FOTO; ?></td>
                                    <td valign="top">:</td>
                                    <td align="left" valign="top"><input type="file" name="foto" class="inputbox" /></td>
                                  </tr>
                                    <tr>
                                      <td align="left" colspan="3" valign="middle">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td align="left" colspan="3" id="contenedorCatchap">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td align="left" colspan="3" valign="middle"><input type="button" name="resetCaptcha" value="Cambiar Imagen Verificadora" onclick="javascript:plugin_do_captcha()" /></td>
                                    </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td align="left" valign="top"><a href="#" onclick="javascript:if(validacion(document.formExperiencia)){document.formExperiencia.submit();}" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('bot_enviar','','<?php echo JURI::base();?>templates/dicoex/images/spanish/bot_enviar_on.jpg',1)" title="<?php echo(_BOT_ENVIAR); ?>"><img src="<?php echo JURI::base();?>templates/dicoex/images/spanish/bot_enviar_off.jpg" alt="bot_enviar" title="<?php echo(_BOT_ENVIAR); ?>" name="bot_enviar" width="82" height="23" vspace="4" border="0" id="bot_enviar" /></a></td>
                                  </tr>
                                </table>
                                <input type="hidden" name="option" value="com_do" />
                                <input type="hidden" name="c" value="formularios" />
                                <input type="hidden" name="plantilla" value="experiencia" />
                                <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
                            </form>
                            <script type="text/javascript">
								plugin_do_captcha();
							</script>
