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
	function display( &$reporte, &$lists )
	{
		global $Itemid;
		//GPTI_ASSETS_URL
		?>
			<script type="text/javascript" src="<?php Juri::base(); ?>components/com_gpti/assets/js/jquery.treeTable.js"></script>
			<div class="gpti_ancho">
				<div class="gpti_centro_xl">
					<form action="<?php echo JRoute::_("index.php?option=com_gpti&Itemid=$Itemid");?>" method="post" name="frmReporte" id="frmReporte">
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
                                                <?php
													$node	= 1;
												?>
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
																<td nowrap="nowrap" align="left"><?php echo $reporte->label;?></td>
																<td><?php echo $reporte->trabajo;?> horas</td>
																<td><?php echo $reporte->duracion;?> días</td>
																<td><?php echo date("d/m/Y H:i", $reporte->inicio);?></td>
																<td><?php echo date("d/m/Y H:i", $reporte->termino);?></td>
															</tr>
                                                        <?php
														if( count( $reporte->proveedores ) ) :
															foreach( $reporte->proveedores as $ip => $proveedor ) :
															if( count( $proveedor->reqs ) ) :
															$nodepro = ++$node;
															?>
															<tr id="node-<?php echo $nodepro;?>" class="nivel-dos child-of-node-1">
																<td nowrap="nowrap" align="left"><?php echo $proveedor->label;?></td>
																<td><?php echo $proveedor->trabajo;?> horas</td>
																<td><?php echo $proveedor->duracion;?> días</td>
																<td><?php echo date("d/m/Y H:i", $proveedor->inicio);?></td>
																<td><?php echo date("d/m/Y H:i", $proveedor->termino);?></td>
															</tr>
                                                            <?php
																	foreach( $proveedor->reqs as $req ) :
																	?>
															<tr id="node-<?php echo ++$node;?>" class="nivel-tres child-of-node-<?php echo $nodepro;?>">
																<td nowrap="nowrap" align="left"><?php echo $req->label;?></td>
																<td><?php echo $req->trabajo;?> horas</td>
																<td><?php echo $req->duracion;?> días</td>
																<td><?php echo date("d/m/Y H:i", $req->inicio);?></td>
																<td><?php echo date("d/m/Y H:i", $req->termino);?></td>
															</tr>
																	<?php
                                                                    endforeach;
															endif;
															endforeach;
														endif;
														?>													
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
                                                <?php
													$node	= 1;
												?>
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
                                                              <?php for( $j=0; $j<12 ; ++$j ) : ?>
																<td><?php echo $reporte->detalle[$j] ? $reporte->detalle[$j].'h' : '&nbsp;';?></td>
                                                              <?php endfor;?>
															</tr>
                                                        <?php
														if( count( $reporte->proveedores ) ) :
															foreach( $reporte->proveedores as $ip => $proveedor ) :
															if( count( $proveedor->reqs ) ) :
															$nodepro = ++$node;
															?>
															<tr id="node-<?php echo $nodepro;?>" class="nivel-dos child-of-node-1">
                                                              <?php for( $j=0; $j<12 ; ++$j ) : ?>
																<td><?php echo $proveedor->detalle[$j] ? $proveedor->detalle[$j].'h' : '&nbsp;';?></td>
                                                              <?php endfor;?>
															</tr>
                                                            <?php
																	foreach( $proveedor->reqs as $req ) :
																	?>
															<tr id="node-<?php echo ++$node;?>" class="nivel-tres child-of-node-<?php echo $nodepro;?>">
                                                              <?php for( $j=0; $j<12 ; ++$j ) : ?>
																<td><?php echo $req->detalle[$j] ? $req->detalle[$j].'h' : '&nbsp;';?></td>
                                                              <?php endfor;?>
															</tr>
																	<?php
                                                                    endforeach;
															endif;
															endforeach;
														endif;
														?>													
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
						jQuery('#gpti_reporte_fechas').treeTable({clickableNodeNames: false, idTabla : '#gpti_reporte_fechas', idTablaD : '#gpti_reporte_f'});
						
						
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
                                                <?php
													$node	= 1;
												?>
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
																<td nowrap="nowrap" align="left"><?php echo $reporte->label;?></td>
																<td><?php echo $reporte->trabajo;?> horas</td>
																<td><?php echo $reporte->duracion;?> días</td>
																<td><?php echo date("d/m/Y H:i", $reporte->inicio);?></td>
																<td><?php echo date("d/m/Y H:i", $reporte->termino);?></td>
															</tr>
                                                        <?php
														if( count( $reporte->proveedores ) ) :
															foreach( $reporte->proveedores as $ip => $proveedor ) :
															if( count( $proveedor->reqs ) ) :
															$nodepro = ++$node;
															?>
															<tr id="data-<?php echo $nodepro;?>" class="nivel-dos child-of-data-1">
																<td nowrap="nowrap" align="left"><?php echo $proveedor->label;?></td>
																<td><?php echo $proveedor->trabajo;?> horas</td>
																<td><?php echo $proveedor->duracion;?> días</td>
																<td><?php echo date("d/m/Y H:i", $proveedor->inicio);?></td>
																<td><?php echo date("d/m/Y H:i", $proveedor->termino);?></td>
															</tr>
                                                            <?php
																	foreach( $proveedor->reqs as $req ) :
																	?>
															<tr id="data-<?php echo ++$node;?>" class="nivel-tres child-of-data-<?php echo $nodepro;?>">
																<td nowrap="nowrap" align="left"><?php echo $req->label;?></td>
																<td><?php echo $req->trabajo;?> horas</td>
																<td><?php echo $req->duracion;?> días</td>
																<td><?php echo date("d/m/Y H:i", $req->inicio);?></td>
																<td><?php echo date("d/m/Y H:i", $req->termino);?></td>
															</tr>
																	<?php
                                                                    endforeach;
															endif;
															endforeach;
														endif;
														?>													
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
                                                <?php
													$node	= 1;
												?>
													<table cellpadding="3" cellspacing="0" border="0" id="gpti_reporte_fechas" class="treeTable gpti_reporte" align="center" >
														<thead>
															<tr>
																<th width="8">&nbsp;</th>
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
                                                              <?php  /*for( $j=0; $j<12 ; ++$j ) :*/ ?>
																<td class="tdfecha" colspan="13"><?php echo $reporte->detalle ? $reporte->detalle : '&nbsp;';?></td>
                                                                <!--div class="tiempo" style="margin-left:70px; width:50px;"></div-->
                                                              <?php /*endfor;*/?>
															</tr>
                                                        <?php
														if( count( $reporte->proveedores ) ) :
															foreach( $reporte->proveedores as $ip => $proveedor ) :
															if( count( $proveedor->reqs ) ) :
															$nodepro = ++$node;
															?>
															<tr id="data-<?php echo $nodepro;?>" class="nivel-dos child-of-data-1">
                                                              <?php /*for( $j=0; $j<12 ; ++$j ) :*/ ?>
																<td class="tdfecha" colspan="13"><?php echo $proveedor->detalle ? $proveedor->detalle : '&nbsp;';?></td>
                                                              <?php /*endfor;*/?>
															</tr>
                                                            <?php
																	foreach( $proveedor->reqs as $req ) :
																	?>
															<tr id="data-<?php echo ++$node;?>" class="nivel-tres child-of-data-<?php echo $nodepro;?>">
                                                              <?php /*for( $j=0; $j<12 ; ++$j ) :*/ ?>
																<td class="tdfecha" colspan="13"><?php echo $req->detalle ? $req->detalle : '&nbsp;';?></td>
                                                              <?php /*endfor;*/?>
															</tr>
																	<?php
                                                                    endforeach;
															endif;
															endforeach;
														endif;
														?>													
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