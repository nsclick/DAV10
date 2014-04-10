 <?php
/**
 * @version		$Id: inicio.php 2011-05-26 Sebastián García Truan
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
defined( '_DO_GPTI' ) or die( 'El acceso directo a este archivo no está permitido.' );

class GPTIVistaInicio
{	
	function display( &$lists )
	{
		global $Itemid;
		//GPTI_ASSETS_URL
		?>
			<script type="text/javascript">
			   jQuery(document).ready(function () {
					jQuery("#filtro_n_interno").keyup(function (e) {
						jQuery("#filtro_n_interno").val( jQuery("#filtro_n_interno").val().toUpperCase() );
					});
					jQuery("#filtro_n_dru").keyup(function (e) {
						jQuery("#filtro_n_dru").val( jQuery("#filtro_n_dru").val().toUpperCase() );
					});
				});
			</script>
            <div class="gpti_core gpti_overflow">
				<?php GPTIVistaGeneral::login_inicio();?>
            </div>
            <div class="gpti_overflow">
                <div class="gpti_caja">
                <?php if( $lists['perfil-Gerente-Proveedor'] || $lists['perfil-Ejecutor'] ) :  ?>
                    <div class="padding_top">
						<div class="gpti_ultimos">
							<table cellpadding="0" cellspacing="0" border="0" >
								<tr>
									<td width="9"><img src="<?php echo GPTI_TEMPLATE_URL; ?>/imagenes/esq_sup_izq.jpg" width="9" height="9" alt="" /></td>
									<td width="100%"></td>
									<td width="9"><img src="<?php echo GPTI_TEMPLATE_URL; ?>/imagenes/esq_sup_der.jpg" width="9" height="9" alt="" /></td>
								</tr>   
								<tr>
									<td width="9"></td>
									<td class="pt" onclick="javascript:actionToggle('td_jqi'); return false;"><h1><?php echo $lists['label-izquierda'];?></h1></td>
									<td width="9"></td>
								</tr>   
								<tr>
									<td><img src="<?php echo GPTI_TEMPLATE_URL; ?>/imagenes/esq_inf_izq.jpg" width="9" height="9" alt="" /></td>
									<td></td>
									<td><img src="<?php echo GPTI_TEMPLATE_URL; ?>/imagenes/esq_inf_der.jpg" width="9" height="9" alt="" /></td>
								</tr>
								<tr class="gpti_blanco">
									<td class="gpti_blanco"></td>
									<td class="gpti_blanco">
										<div id="td_jqi">
											<?php echo $lists['lista-izquierda'];?>
										</div>
									</td>
									<td class="gpti_blanco"></td>
								</tr>                  
							</table>
						</div>
					</div>                
                <?php else : ?>
                    <div class="gpti_ingresar gpti_overflow" align="left">
                        <span>Ingresa</span> <br /> un nuevo requerimiento <a href="<?php echo JRoute::_("index.php?option=com_gpti&Itemid=".$lists['menu-ingresar']);?>" title="Ingresar un nuevo requerimiento">aquí</a>
                    </div>
                <?php endif; ?>
                </div>
                <div class="gpti_caja">
                	<div class="gpti_busqueda">
                        <h1>Búsqueda de Requerimientos</h1>
						<form name="frmFiltro" id="frmFiltro" action="<?php echo JRoute::_("index.php?option=com_gpti&Itemid=$Itemid");?>" method="post"> 
                            <div class="gpti_select">
                                <?php echo $lists['select-proyecto']; ?>
                            </div>                
                            <div class="gpti_select">
                                <?php echo $lists['select-estado']; ?>
                            </div>   
                          <?php if( $lists['perfil-Admin'] ): ?>
                            <div class="gpti_select">
								<?php echo  $lists['select-gerencia']; ?>                            
							</div>
                          <?php endif; ?>
                            <div class="gpti_input">
                                <?php echo $lists['text-numero-dru']; ?>&nbsp;<?php echo $lists['text-numero-int']; ?>
                            </div>
                            <div class="gpti_boton">
								<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmFiltro','buscar'); return false;" title="Buscar">Buscar</a>
                            </div>
							<input type="hidden" name="option" value="com_gpti" />
							<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
							<input type="hidden" name="c" value="requerimientos" />
							<input type="hidden" name="task" value="" />
                        </form>
                    </div>
                </div>
                <div class="gpti_caja">
                    <div class="padding_top">
						<div class="gpti_ultimos">
							<table cellpadding="0" cellspacing="0" border="0" >
								<tr>
									<td width="9"><img src="<?php echo GPTI_TEMPLATE_URL; ?>/imagenes/esq_sup_izq.jpg" width="9" height="9" alt="" /></td>
									<td width="100%"></td>
									<td width="9"><img src="<?php echo GPTI_TEMPLATE_URL; ?>/imagenes/esq_sup_der.jpg" width="9" height="9" alt="" /></td>
								</tr>   
								<tr>
									<td width="9"></td>
									<td class="pt" onclick="javascript:actionToggle('td_jq'); return false;"><h1><?php echo $lists['label-derecha'];?></h1></td>
									<td width="9"></td>
								</tr>   
								<tr>
									<td><img src="<?php echo GPTI_TEMPLATE_URL; ?>/imagenes/esq_inf_izq.jpg" width="9" height="9" alt="" /></td>
									<td></td>
									<td><img src="<?php echo GPTI_TEMPLATE_URL; ?>/imagenes/esq_inf_der.jpg" width="9" height="9" alt="" /></td>
								</tr>
								<tr class="gpti_blanco">
									<td class="gpti_blanco"></td>
									<td class="gpti_blanco">
										<div id="td_jq">
											<?php echo $lists['lista-derecha'];?>
										</div>
									</td>
									<td class="gpti_blanco"></td>
								</tr>                  
							</table>
						</div>
					</div>
                </div>
            </div>
    	<?php
	}
}

?>