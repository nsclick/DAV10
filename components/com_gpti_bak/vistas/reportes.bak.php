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

class GPTIVistaReportes
{	
	function display( &$lists )
	{
		global $Itemid;
		//GPTI_ASSETS_URL
		?>
			<script type="text/javascript" src="<?php Juri::base(); ?>components/com_gpti/assets/js/jquery.treeTable.js"></script>
			<div class="gpti_ancho">
				<div class="gpti_centro_xl">
					<form action="<?php echo JRoute::_("index.php?option=com_gpti&Itemid=$Itemid");?>" method="post" name="frmReporte" id="frmReporte" enctype="multipart/form-data">
					<div id="gpti_msj" class="gpti_msj"></div>
						<table cellpadding="0" cellspacing="0" border="0" class="gpti_cerrardru">
							<tr>
								<td class="gpti_detalle">
									<div class="gpti_boton btn_ancho_x">
										<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmReporte','desarrollo'); return false;" title="Proyectos en Desarrollo">Proyectos en Desarrollo</a>
									</div>
			
									<div class="gpti_boton btn_ancho_x">
										<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmReporte','espera'); return false;" title="Proyectos en Espera">Proyectos en Espera</a>
									</div>
								</td>
							</tr>     
							<tr>
								<td class="gpti_detalle">&nbsp;</td>
							</tr>     
						</table>
					<input type="hidden" name="option" value="com_gpti" />
					<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
					<input type="hidden" name="c" value="reportes" />
					<input type="hidden" name="task" value="" />
					</form>
				</div>
				<br /><br />

			<?php if( $lists['reporte-tarea'] == 'desarrollo' ) :?>
				<script type="text/javascript">
					jQuery(document).ready( function(){
													 
						jQuery('#mover_h').click(function () {
							jQuery("#izquierdo_h").animate({ width : 'toggle', opacity : 'toggle' });
							jQuery("#mover_h h1").toggle();
						});   	
						
						jQuery('#gpti_reporte_h').treeTable({clickableNodeNames:true, idTabla : '#gpti_reporte_h', idTablaD : '#gpti_reporte_horas' });
						jQuery('#gpti_reporte_horas').treeTable({clickableNodeNames:false, idTabla : '#gpti_reporte_horas', idTablaD : '#gpti_reporte_h' });
					});
				</script>					
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_tabla">
					<tr>
						<td class="gpti_centro_xl">
							<div class="gpti_titulo"><h1>Proyectos en Desarrollo</h1></div>
						</td>
						<td class="gpti_derecha" align="right" valign="bottom">
							<div class="gpti_expande_r">
								<table cellpadding="0" cellspacing="0" border="0" >
									<tr>
										<td width="9" align="left"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_izq.jpg" width="9" height="9" alt="" /></td>
										<td width="100%"></td>
										<td width="9" align="right"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_der.jpg" width="9" height="9" alt="" /></td>
									</tr>   
									<tr>
										<td></td>
										<td class="pt" id="mover_h"><h1 id="label_dt" class="in">Ver Detalle</h1><h1 id="label_rs" class="out" style="display:none;">Ver Resumen</h1></td>
										<td></td>
									</tr>   
									<tr>
										<td align="left"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_izq.jpg" width="9" height="9" alt="" /></td>
										<td></td>
										<td abbr="right"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_der.jpg" width="9" height="9" alt="" /></td>
									</tr>                
								</table>
							</div>
						</td>
					</tr>	
				</table>                
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_tabla">
					<tr>
						<td>
							<div class="gpti_centro_xxxl gpti_float_l gpti_overflow">
								<div id="gpti_msj" class="gpti_msj"></div>
								<div class="reporte-div">
									<div id="izquierdo_h" class="gpti_float_l gpti_centro_xxl">
										<table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_filtro">
											<tr>
												<td align="left" width="10"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_izq_busqueda.jpg" width="10" height="10" alt="" /></td>
												<td width="100%"></td>
												<td align="right" width="10"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_der_busqueda.jpg" width="10" height="10" alt="" /></td>
											</tr>
											<tr>
												<td colspan="3">
													<table cellpadding="3" cellspacing="0" border="0" id="gpti_reporte_h" class="treeTable gpti_reporte" align="center" width="100%" >
														<thead>
															<tr>
																<th nowrap="nowrap" width="223">Nombre de la Tarea</th>
																<th nowrap="nowrap" width="123">Trabajo</th>
																<th nowrap="nowrap" width="123">Duración</th>
																<th nowrap="nowrap" width="123">Comienzo</th>
																<th nowrap="nowrap" width="123">Fin</th>
															</tr>												
														</thead>
														<tbody>
															<tr id="node-1" class="nivel-uno" >
																<td nowrap="nowrap" align="left">CARTERA DE PROYECTOS EN DESARROLLO</td>
																<td>5,360 horas</td>
																<td>232 días</td>
																<td>5/2/2011 9:00</td>
																<td>3/20/2012 19:00</td>
															</tr>
															<tr id="node-2" class="nivel-dos child-of-node-1">
																<td nowrap="nowrap" align="left">TISAL - Grupo 1 - Laura Cerda</td>
																<td>1,840 horas</td>
																<td>149 días</td>
																<td>5/2/2011 9:00</td>
																<td>11/24/2011 19:00</td>
															</tr>													
															<tr id="node-3" class="nivel-tres child-of-node-2">
																<td nowrap="nowrap" align="left">Implementación Sistema de Adm. de Camas</td>
																<td>880 horas</td>
																<td>110 días</td>
																<td>5/2/2011 9:00</td>
																<td>9/30/2011 19:00</td>
															</tr>
															<tr id="node-4" class="nivel-tres child-of-node-2">
																<td nowrap="nowrap" align="left">Gestión de Camas - para el administrador de camas</td>
																<td>480 horas</td>
																<td>60 días	</td>
																<td>6/10/2011 9:00</td>
																<td>9/1/2011 19:00</td>
														</tr>
															<tr id="node-5" class="nivel-tres child-of-node-2">
																<td nowrap="nowrap" align="left">Proyecto Esterilizacion Etapa II	</td>
																<td>480 horas</td>
																<td>60 días</td>
																<td>9/2/2011 9:00</td>
																<td>11/24/2011 19:00</td>
															</tr>
															<tr id="node-6" class="nivel-dos child-of-node-1">
																<td nowrap="nowrap" align="left">TISAL  Grupo 2 - Patricio Silva	1,</td>
																<td>760 horas</td>
																<td>220 días</td>
																<td>5/18/2011 9:00</td>
																<td>3/20/2012 19:00</td>
															</tr>
															<tr id="node-7" class="nivel-tres child-of-node-6">
																<td nowrap="nowrap" align="left">Desarrollar modulo CTI para Central de Traslados	</td>
																<td>480 horas</td>
																<td>60 días	</td>
																<td>5/18/2011 9:00</td>
																<td>8/9/2011 19:00</td>
															</tr>
															<tr id="node-8" class="nivel-tres child-of-node-6">
																<td nowrap="nowrap" align="left">Tisal</td>
																<td>480 horas</td>
																<td>0 días</td>
																<td>5/18/2011 9:00</td>
																<td>8/9/2011 19:00</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
												<td align="left"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_izq_busqueda.jpg" width="10" height="10" alt="" /></td>
												<td></td>
												<td align="right"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_der_busqueda.jpg" width="10" height="10" alt="" /></td>
											</tr>
										</table>
									</div>											
									<div id="derecho_h" class="gpti_float_l gpti_centro_xxl">	
										<table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_filtro">
											<tr>
												<td align="left" width="10"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_izq_busqueda.jpg" width="10" height="10" alt="" /></td>
												<td width="100%"></td>
												<td align="right" width="10"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_der_busqueda.jpg" width="10" height="10" alt="" /></td>
											</tr>
											<tr>
												<td colspan="3">
													<table cellpadding="3" cellspacing="0" border="0" id="gpti_reporte_horas" class="treeTable gpti_reporte" align="center" width="100%" >
														<thead>
															<tr>
																<th nowrap="nowrap">Enero</th>
																<th nowrap="nowrap">Febrero</th>
																<th nowrap="nowrap">Marzo</th>
																<th nowrap="nowrap">Abril</th>
																<th nowrap="nowrap">Mayo</th>
																<th nowrap="nowrap">Junio</th>
																<th nowrap="nowrap">Julio</th>
																<th nowrap="nowrap">Agosto</th>
																<th nowrap="nowrap">Septiembre</th>
																<th nowrap="nowrap">Octubre</th>
																<th nowrap="nowrap">Noviembre</th>
																<th nowrap="nowrap">Diciembre</th>
															</tr>												
														</thead>
														<tbody>
															<tr id="node-1" class="nivel-uno" >
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td>240h</td>
																<td></td>
																<td></td>
																<td>48</td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
															</tr>
															<tr id="node-2"  class="nivel-dos child-of-node-1">
																<td></td>
																<td></td>
																<td>240h</td>
																<td></td>
																<td>240h</td>
																<td></td>
																<td></td>
																<td>48</td>
																<td>240h</td>
																<td></td>
																<td>48</td>
																<td></td>
															</tr>													
															<tr id="node-3"  class="nivel-tres child-of-node-2">
																<td></td>
																<td></td>
																<td>48</td>
																<td></td>
																<td>240h</td>
																<td></td>
																<td>48</td>
																<td>240h</td>
																<td></td>
																<td>240h</td>
																<td></td>
																<td></td>															
															</tr>
															<tr id="node-4" class="nivel-tres child-of-node-2">
																<td></td>
																<td></td>
																<td>48</td>
																<td>48</td>
																<td>240h</td>
																<td></td>
																<td>240h</td>
																<td></td>
																<td></td>															
																<td></td>
																<td>240h</td>
																<td></td>
															</tr>
															<tr id="node-5" class="nivel-tres child-of-node-2">
																<td></td>
																<td>48</td>
																<td>240h</td>
																<td></td>
																<td></td>
																<td></td>
																<td>48</td>
																<td>240h</td>
																<td></td>
																<td>240h</td>
																<td></td>
																<td></td>															
															</tr>
															<tr id="node-6" class="nivel-dos child-of-node-1">
																<td></td>
																<td></td>
																<td></td>
																<td></td>															
																<td></td>
																<td></td>
																<td></td>
																<td>240h</td>
																<td>48</td>
																<td>48</td>
																<td>240h</td>
																<td>240h</td>
															</tr>
															<tr id="node-7" class="nivel-tres child-of-node-6">
																<td></td>
																<td></td>
																<td>48</td>
																<td></td>
																<td></td>
																<td></td>
																<td>48</td>
																<td>240h</td>
																<td></td>
																<td></td>
																<td>240h</td>
																<td>240h</td>															
															</tr>
															<tr id="node-8" class="nivel-tres child-of-node-6">
																<td></td>
																<td></td>
																<td>240h</td>
																<td></td>
																<td>48</td>
																<td>240h</td>
																<td></td>
																<td>48</td>
																<td></td>
																<td>240h</td>
																<td></td>
																<td></td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
												<td align="left"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_izq_busqueda.jpg" width="10" height="10" alt="" /></td>
												<td></td>
												<td align="right"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_der_busqueda.jpg" width="10" height="10" alt="" /></td>
											</tr>
										</table>
									</div>		
								</div>		
							</div>
						</td>
					</tr>
				</table>
			
			<?php elseif( $lists['reporte-tarea'] == 'espera' ) :?>
						
				<script type="text/javascript">
					jQuery(document).ready( function(){
						jQuery('#mover_f').click(function () {
							jQuery("#izquierdo_f").animate({ width : 'toggle', opacity : 'toggle' });
							jQuery("#mover_f h1").toggle();
						});
						
						jQuery('#gpti_reporte_f').treeTable({clickableNodeNames:true, idTabla : '#gpti_reporte_f', idTablaD : '#gpti_reporte_fechas' });
						jQuery('#gpti_reporte_fechas').treeTable({clickableNodeNames: false, idTabla : '#gpti_reporte_fechas', idTablaD : '#gpti_reporte_f' });
					});
				</script>
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_tabla">
					<tr>
						<td class="gpti_centro_xl">
							<div class="gpti_titulo"><h1>Proyectos en Espera</h1></div>
						</td>
						<td class="gpti_derecha" align="right" valign="bottom">
							<div class="gpti_expande_r">
								<table cellpadding="0" cellspacing="0" border="0" >
									<tr>
										<td width="9" align="left"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_izq.jpg" width="9" height="9" alt="" /></td>
										<td width="100%"></td>
										<td width="9" align="right"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_der.jpg" width="9" height="9" alt="" /></td>
									</tr>   
									<tr>
										<td></td>
										<td class="pt" id="mover_f"><h1 id="label_dt" class="in">Ver Detalle</h1><h1 id="label_rs" class="out" style="display:none;">Ver Resumen</h1></td>
										<td></td>
									</tr>   
									<tr>
										<td align="left"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_izq.jpg" width="9" height="9" alt="" /></td>
										<td></td>
										<td abbr="right"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_der.jpg" width="9" height="9" alt="" /></td>
									</tr>                
								</table>
							</div>
						</td>
					</tr>	
				</table>				
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_tabla">
					<tr>
						<td>										
							<div class="gpti_centro_xxxl gpti_float_l gpti_overflow">										
								<div class="reporte-div">
									<div id="izquierdo_f" class="gpti_float_l gpti_centro_xxl">
										<table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_filtro">
											<tr>
												<td align="left" width="10"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_izq_busqueda.jpg" width="10" height="10" alt="" /></td>
												<td width="100%"></td>
												<td align="right" width="10"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_der_busqueda.jpg" width="10" height="10" alt="" /></td>
											</tr>
											<tr>
												<td colspan="3">
													<table cellpadding="3" cellspacing="0" border="0" id="gpti_reporte_f" class="treeTable gpti_reporte" align="center" width="100%" >
														<thead>
															<tr>
																<th nowrap="nowrap" width="223">Nombre de la Tarea</th>
																<th nowrap="nowrap" width="123">Trabajo</th>
																<th nowrap="nowrap" width="123">Duración</th>
																<th nowrap="nowrap" width="123">Comienzo</th>
																<th nowrap="nowrap" width="123">Fin</th>
															</tr>												
														</thead>
														<tbody>
															<tr id="data-1" class="nivel-uno" >
																<td nowrap="nowrap" align="left">CARTERA DE PROYECTOS EN DESARROLLO</td>
																<td>5,360 horas</td>
																<td>232 días</td>
																<td>5/2/2011 9:00</td>
																<td>3/20/2012 19:00</td>
															</tr>
															<tr id="data-2"  class="nivel-dos child-of-data-1">
																<td nowrap="nowrap" align="left">TISAL - Grupo 1 - Laura Cerda</td>
																<td>1,840 horas</td>
																<td>149 días</td>
																<td>5/2/2011 9:00</td>
																<td>11/24/2011 19:00</td>
															</tr>													
															<tr id="data-3"  class="nivel-tres child-of-data-2">
																<td nowrap="nowrap" align="left">Implementación Sistema de Adm. de Camas</td>
																<td>880 horas</td>
																<td>110 días</td>
																<td>5/2/2011 9:00</td>
																<td>9/30/2011 19:00</td>
															</tr>
															<tr id="data-4" class="nivel-tres child-of-data-2">
																<td nowrap="nowrap" align="left">Gestión de Camas - para el administrador de camas</td>
																<td>480 horas</td>
																<td>60 días	</td>
																<td>6/10/2011 9:00</td>
																<td>9/1/2011 19:00</td>
															</tr>
															<tr id="data-5" class="nivel-tres child-of-data-2">
																<td nowrap="nowrap" align="left">Proyecto Esterilizacion Etapa II	</td>
																<td>480 horas</td>
																<td>60 días</td>
																<td>9/2/2011 9:00</td>
																<td>11/24/2011 19:00</td>
															</tr>
															<tr id="data-6" class="nivel-dos child-of-data-1">
																<td nowrap="nowrap" align="left">TISAL  Grupo 2 - Patricio Silva	1,</td>
																<td>760 horas</td>
																<td>220 días</td>
																<td>5/18/2011 9:00</td>
																<td>3/20/2012 19:00</td>
															</tr>
															<tr id="data-7" class="nivel-tres child-of-data-6">
																<td nowrap="nowrap" align="left">Desarrollar modulo CTI para Central de Traslados	</td>
																<td>480 horas</td>
																<td>60 días	</td>
																<td>5/18/2011 9:00</td>
																<td>8/9/2011 19:00</td>
															</tr>
															<tr id="data-8" class="nivel-tres child-of-data-6">
																<td nowrap="nowrap" align="left">Tisal</td>
																<td>480 horas</td>
																<td>0 días</td>
																<td>5/18/2011 9:00</td>
																<td>8/9/2011 19:00</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
												<td align="left"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_izq_busqueda.jpg" width="10" height="10" alt="" /></td>
												<td></td>
												<td align="right"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_der_busqueda.jpg" width="10" height="10" alt="" /></td>
											</tr>
										</table>
									</div>											
									<div id="derecho_f" class="gpti_float_l gpti_centro_xxl">	
										<table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_filtro">
											<tr>
												<td align="left" width="10"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_izq_busqueda.jpg" width="10" height="10" alt="" /></td>
												<td width="100%"></td>
												<td align="right" width="10"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_der_busqueda.jpg" width="10" height="10" alt="" /></td>
											</tr>
											<tr>
												<td colspan="3">
													<table cellpadding="3" cellspacing="0" border="0" id="gpti_reporte_fechas" class="treeTable gpti_reporte" align="center" >
														<thead>
															<tr>
																<th width="8"></th>
																<th width="62">Enero</th>
																<th width="58">Febrero</th>
																<th width="62">Marzo</th>
																<th width="60">Abril</th>
																<th width="62">Mayo</th>
																<th width="60">Junio</th>
																<th width="62">Julio</th>
																<th width="62">Agosto</th>
																<th width="60">Septiembre</th>
																<th width="62">Octubre</th>
																<th width="60">Noviembre</th>
																<th width="62">Diciembre</th>
															</tr>												
														</thead>
														<tbody>
															<tr id="data-1" class="nivel-uno" >
																<td></td>
																<td colspan="12"><div class="tiempo" style="margin-left:50px; width:100px;"></div></td>
															</tr>
															<tr id="data-2"  class="nivel-dos child-of-data-1">
																<td></td>
																<td colspan="12"><div class="tiempo" style="margin-left:350px; width:40px;"></div></td>
															</tr>													
															<tr id="data-3"  class="nivel-tres child-of-data-2">
																<td></td>
																<td colspan="12"><div class="tiempo" style="margin-left:250px; width:30px;"></div></td>														
															</tr>
															<tr id="data-4" class="nivel-tres child-of-data-2">
																<td></td>
																<td colspan="12"><div class="tiempo" style="margin-left:150px; width:150px;"></div></td>
															</tr>
															<tr id="data-5" class="nivel-tres child-of-data-2">
																<td></td>
																<td colspan="12"><div class="tiempo" style="margin-left:90px; width:200px;"></div></td>															
															</tr>
															<tr id="data-6" class="nivel-dos child-of-data-1">
																<td></td>
																<td colspan="12"><div class="tiempo" style="margin-left:5px; width:30px;"></div></td>
															</tr>
															<tr id="data-7" class="nivel-tres child-of-data-6">
																<td></td>
																<td colspan="12"><div class="tiempo" style="margin-left:60px; width:40px;"></div></td>														
															</tr>
															<tr id="data-8" class="nivel-tres child-of-data-6">
																<td></td>
																<td colspan="12"><div class="tiempo" style="margin-left:70px; width:50px;"></div></td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
												<td align="left"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_izq_busqueda.jpg" width="10" height="10" alt="" /></td>
												<td></td>
												<td align="right"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_der_busqueda.jpg" width="10" height="10" alt="" /></td>
											</tr>
										</table>
									</div>		
								</div>		
							</div>
						</td>
					</tr>
				</table>

			<?php endif; ?>
			
			
		</div>

	<?php
	}
}

?>