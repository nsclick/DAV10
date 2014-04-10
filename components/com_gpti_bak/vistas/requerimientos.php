 <?php
/**
 * @version		$Id: requerimientos.php 2011-05-26 Sebastián García Truan
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

class GPTIVistaRequerimientos
{	

	function display( &$lists, $rows )
	{
		global $Itemid;
		//GPTI_ASSETS_URL
		?>
			<script type="text/javascript" src="<?php Juri::base(); ?>components/com_gpti/assets/js/jquery.cookie.js"></script>
			<script type="text/javascript">
				jQuery(document).ready( function(){
					jQuery("#filtro_n_interno").keyup(function (e) {
						jQuery("#filtro_n_interno").val( jQuery("#filtro_n_interno").val().toUpperCase() );
					});
					jQuery("#filtro_n_dru").keyup(function (e) {
						jQuery("#filtro_n_dru").val( jQuery("#filtro_n_dru").val().toUpperCase() );
					});
					if( jQuery.cookie('columna') == 'cerrar')
					{
						jQuery('#label').text('Abrir');
						jQuery('div.gpti_derecha').css({ width : 0, opacity : 0 });
						jQuery('div.gpti_centro_xl').css({ width : 770 });
					}
					if( jQuery.cookie('columna') == 'abrir')
					{
						jQuery('#label').text('Cerrar');
						jQuery('div.gpti_centro_xl').css({ width : 595 });
						jQuery('div.gpti_derecha').css({ width : 175, opacity : 1   });
					}
				});
			</script>
            <div class="gpti_ancho">
                <table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_tabla">
            		<tr>
                     	<td class="gpti_centro_xl">
							<div class="gpti_titulo"><h1>Búsquedas</h1></div>
                        </td>
                     	<td class="gpti_derecha" align="right" valign="bottom">
                            <div class="gpti_expande">
                                <table cellpadding="0" cellspacing="0" border="0" >
                                    <tr>
                                        <td width="9" align="left"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_izq.jpg" width="9" height="9" alt="" /></td>
                                        <td width="100%"></td>
                                        <td width="9" align="right"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_der.jpg" width="9" height="9" alt="" /></td>
                                    </tr>   
                                    <tr>
                                        <td></td>
                                        <td class="pt" onclick="javascript:admColumna( 'gpti_centro_xl' , 'gpti_derecha', 'label' ); return false;"><h1 id="label">Cerrar</h1></td>
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
						<div class="gpti_centro_xl gpti_float_l">  
							<form name="frmFiltro" id="frmFiltro" action="<?php echo JRoute::_("index.php?option=com_gpti&Itemid=$Itemid");?>" method="post"> 
							<div id="gpti_msj" class="gpti_msj"></div>                     
             	   			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_filtro">
								<tr>
                                    <td><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_izq_busqueda.jpg" width="10" height="10" alt="" /></td>
                                    <td></td>
                                    <td><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_der_busqueda.jpg" width="10" height="10" alt="" /></td>
                                </tr>
								<tr>
                                    <td></td>
                                    <td>
                                    	<div class="gpti_overflow">
                                        	<div class="gpti_float_l gpti_overflow contenedor">
                                                <div class="columna_r gpti_float_l">
                                                    <div><?php echo $lists['select-proyecto']; ?></div>
                                                    <div><?php echo $lists['calendario-fecha-desde']; ?></div>
                                                    <div><?php echo $lists['calendario-fecha-hasta']; ?></div>
                                                </div>
                                                <div class="columna_c gpti_float_l">
                                                    <div><?php echo $lists['text-proyecto']; ?></div>
													
													<?php if( !($lists['perfil-Usuario'] || $lists['perfil-Gerencia']) ) : ?>
                                                    	<div><?php echo $lists['select-gerencia']; ?></div>
													<?php endif; ?>
													
                                                    <div><?php echo $lists['select-responsables-modulos']; ?></div>
													<?php /*; ?>
                                                    <div><?php echo $lists['select-clasificacion']; ?></div>
													<?php */ ?>
                                                </div>
                                                <div class="columna_i gpti_float_l">
                                                    <div><?php echo $lists['select-estado']; ?></div>
                                                    <div><?php echo $lists['text-numero-dru']; ?></div>
                                                    <div><?php echo $lists['text-numero-int']; ?></div>
                                                </div>
                                                <div class="columna_i gpti_float_l">
                                                    <div class="radio"><?php echo $lists['radio-todos']; ?></div>
                                                    <div class="radio"><?php echo $lists['radio-dentro-plazo']; ?></div>
                                                    <div class="radio"><?php echo $lists['radio-atrasados']; ?></div>
                                                    <div class="radio"><?php echo $lists['radio-comite']; ?></div>
                                                </div>
                                            </div>
                                            <div class="gpti_float_r contenedor">
												<div class="gpti_boton">
													<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmFiltro','buscar'); return false;" title="Buscar">Buscar</a>
												</div>
												<div class="gpti_boton off">
													<a href="javascript:void(0);" onclick="javascript:GPTI_Reset('frmFiltro'); return false;" title="Limpiar">Limpiar</a>
												</div>
											</div>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
								<tr>
                                    <td><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_izq_busqueda.jpg" width="10" height="10" alt="" /></td>
                                    <td></td>
                                    <td><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_der_busqueda.jpg" width="10" height="10" alt="" /></td>
                                </tr>
                        	</table>
							<input type="hidden" name="option" value="com_gpti" />
							<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
							<input type="hidden" name="c" value="requerimientos" />
							<input type="hidden" name="task" value="" />
							</form>
							<?php if( count($rows)) :  ?>
								<form name="frmBuscar" id="frmBuscar" action="<?php echo JRoute::_("index.php?option=com_gpti&Itemid=$Itemid");?>" method="post">
								<table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_filtro">
									<tr>
										<td align="left" width="10"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_izq_busqueda.jpg" width="10" height="10" alt="" /></td>
										<td width="575"></td>
										<td align="right" width="10"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_der_busqueda.jpg" width="10" height="10" alt="" /></td>
									</tr>
									<tr>
										<td colspan="3">
										<?php if( $lists['perfil-Admin'] ) : ?>
											<div class="gpti_centro_xl scroll-x" >
											<table cellpadding="3" cellspacing="0" border="0" width="100%" class="gpti_resultados" id="gpti_resultados" summary="">
												<thead>
													<tr>
														<th>Prioridad</th>
														<th>Nº DRU</th>
														<th>Nº Interno</th>
														<th>Gerencia</th>
														<th>Responsable<br />Módulo</th>
														<th>Estado Actual</th>
														<th>Nombre del<br />Proyecto</th>
                                                        <th>Nombre del<br />Requerimiento</th>
														<th>Fecha <br />Ingreso</th>
														<th>Fecha Cambio<br />Estado</th>
														
														<th>Fecha Inicio<br />Desarrollo</th>
														<th>Fecha Término<br />Desarrollo</th>
														<th>Días Status</th>
													</tr>
												</thead>
												<tbody>
												<?php foreach( $rows as $i => $row ) :  ?>
													<tr bgcolor="<?php echo ($i % 2)?'#EBEBEB':'#FFFFFF';?>">
														<td align="center"><?php echo $row->REQ_PRIORIDAD; ?></td>
														<td nowrap="nowrap"><a href="<?php echo $row->REQ_LINK; ?>" title="<?php echo $row->REQ_NOMBRE; ?>"><?php echo $row->REQ_DRU; ?></a></td>
														<td nowrap="nowrap"><?php echo $row->REQ_NRO_INTERNO; ?></td>
														<td nowrap="nowrap"><?php echo $row->GER_NOMBRE; ?></td>
														<td nowrap="nowrap">
															<?php if( count($row->REQ_ENCARGADOS) ): ?>
																<?php $ir=0; foreach( $row->REQ_ENCARGADOS as $responsable ):?>
																	<?php echo ($ir++ ? ', ':'').$responsable->name; ?>
																<?php endforeach; ?>
															<?php endif; ?>
														</td>
														<td nowrap="nowrap"><?php echo $row->RES_NOMBRE; ?></td>
														<td nowrap="nowrap"><?php echo $row->REQ_PROYECTO ? $row->REQ_PROYECTO : '-'; ?></td>
                                                        <td nowrap="nowrap"><?php echo $row->REQ_NOMBRE; ?></td>
														<td nowrap="nowrap"><?php echo $row->REQ_FECHA_CREACION; ?></td>
														<td nowrap="nowrap"><?php echo $row->REQ_FECHA_MODIFICACION ? $row->REQ_FECHA_MODIFICACION : '-'; ?></td>
														<td nowrap="nowrap"><?php echo $row->FECHA_PROVEEDOR_INICIO ? $row->FECHA_PROVEEDOR_INICIO : '-'; ?></td>
														<td nowrap="nowrap"><?php echo $row->FECHA_PROVEEDOR_TERMINO ? $row->FECHA_PROVEEDOR_TERMINO : '-'; ?></td>
														<td nowrap="nowrap"><?php echo $row->REQ_DIAS_STATUS ? $row->REQ_DIAS_STATUS : '-'; ?></td>
													</tr>
												<?php endforeach; ?>
												</tbody>
											</table>
										</div>
										<?php else: ?>
											<table cellpadding="3" cellspacing="0" border="0" width="100%" class="gpti_resultados" id="gpti_resultados" summary="">
												<thead>
													<tr>
														<th>Prioridad</th>
														<th>Nº DRU</th>
														<th>Nº Interno</th>
														<th>Estado</th>
														<th>Proyecto</th>
														<th>Nombre</th>
														<th>Fecha de<br />Ingreso</th>
														<th>Gerencia</th>
													</tr>
												</thead>
												<tbody>
												<?php foreach( $rows as $i => $row ) :  ?>
													<tr bgcolor="<?php echo ($i % 2)?'#EBEBEB':'#FFFFFF';?>">
														<?php /* ?><td><input type="radio" name="REQ_ID" value="<?php echo $row->REQ_ID; ?>" /></td><?php */ ?>
														<td align="center"><?php echo $row->REQ_PRIORIDAD; ?></td>
														<td nowrap="nowrap"><a href="<?php echo $row->REQ_LINK; ?>" title="<?php echo $row->REQ_DRU; ?>"><?php echo $row->REQ_DRU; ?></a></td>
														<td nowrap="nowrap"><?php echo $row->REQ_NRO_INTERNO; ?></td>
														<td><?php echo $row->RES_NOMBRE; ?></td>
														<td><?php echo $row->REQ_PROYECTO; ?></td>
														<td><?php echo $row->REQ_NOMBRE; ?></td>
														<td><?php echo $row->REQ_FECHA_CREACION; ?></td>
														<td><?php echo $row->GER_NOMBRE; ?></td>
													</tr>
												<?php endforeach; ?>
												</tbody>
											</table>
										<?php endif; ?>
											<input type="hidden" name="option" value="com_gpti" />
											<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
											<input type="hidden" name="task" value="" />
										</td>
									</tr>
									<tr>
										<td></td>
										<td>
										
											<?php /* if( GPTIHelperACL::check('req_cerrar') ) : ?>
												<div class="gpti_boton">
													<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmBuscar','cerrar'); return false;" title="Cerrar">Cerrar</a>
												</div>
											<?php endif; */ ?>
											<?php /* if( GPTIHelperACL::check('req_aceptacion') ) : ?>
												<div class="gpti_boton">
													<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmBuscar','aceptar'); return false;" title="Aceptar">Aceptar</a>
												</div>
											<?php endif; */?>
											<?php /* if( GPTIHelperACL::check('req_prueba_aprueba') ) : ?>
												<div class="gpti_boton">
													<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmBuscar','aceptar_prueba'); return false;" title="Aceptar Prueba">Aceptar Prueba</a>
												</div>
											<?php endif; */ ?>
											<?php /* if( GPTIHelperACL::check('req_ver') ) : ?>
												<div class="gpti_boton">
													<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmBuscar','ver'); return false;" title="Ver Requerimiento">Ver Requerimiento</a>
												</div>
											<?php endif; ?>
											<?php if( GPTIHelperACL::check('req_ingresar') &&  !$lists['perfil-Usuario'] ) : ?>
												<div class="gpti_boton">
													<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmBuscar','editar'); return false;" title="Modificar">Modificar</a>
												</div>
											<?php endif; */ ?>
											<?php /*  if( GPTIHelperACL::check('req_derivar') ) : ?>
											<div class="gpti_boton">
												<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmBuscar','asignar_p'); return false;" title="Asignar Proveedor">Asignar Proveedor</a>
											</div>
											<?php endif; */ ?>											
											<?php /* if( GPTIHelperACL::check('req_programar') ) : ?>
											<div class="gpti_boton">
												<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmBuscar','planificar'); return false;" title="Programar">Programar</a>
											</div>
											<?php endif; */ ?>
										</td>
										<td></td>
									</tr>
									<tr>
										<td align="left"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_izq_busqueda.jpg" width="10" height="10" alt="" /></td>
										<td></td>
										<td align="right"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_der_busqueda.jpg" width="10" height="10" alt="" /></td>
									</tr>
								</table>
								</form>
							<?php endif; ?>
                        </div>
                     	<div align="right" class="gpti_derecha gpti_float_l">
                            <?php GPTIVistaGeneral::listas_derecha();?>
                            <br /><br /><br />
                    		<?php GPTIVistaGeneral::login();?>
						</div>
                        </td>
                    </tr>
               	</table>
            </div>
    	<?php
	}
	
	function ver( &$lists, $row )
	{
		//echo '<pre>'; print_r( $row );echo '</pre>'; exit; 
		global $Itemid;
		//GPTI_ASSETS_URL
		?>
            <div class="gpti_ancho">
                <table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_tabla">
            		<tr>
						<td class="gpti_centro_xl">
							<div class="gpti_titulo">
								<h1>[ <?php echo $row->REQ_DRU; ?> ] <?php echo $row->REQ_NOMBRE; ?></h1>
								<h2>Estado : <?php echo $row->ESTADO; ?></h2>				
							</div>
                        </td>
                     	<td class="gpti_derecha" align="right">&nbsp;</td>
					</tr>	
				</table>
                <table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_tabla">
            		<tr>
						<td>
							<div class="gpti_izquierda gpti_float_l" align="center">
								<?php GPTIVistaGeneral::login();?>
							</div>
							<div class="gpti_centro gpti_float_l">
								<form action="<?php echo JRoute::_("index.php?option=com_gpti&Itemid=$Itemid");?>" method="post" name="frmRequerimiento" id="frmRequerimiento" >
								<?php if( $lists['msj'] ): ?>
									<div id="gpti_msj" class="gpti_msj" align="left"><ul class="correcto"><li><?php echo $lists['msj']; ?></li></ul></div>
								<?php endif; ?>
									<table cellpadding="0" cellspacing="0" border="0" class="gpti_cerrardru">
										<tr>
											<td class="gpti_detalle">
												<div class="gpti_overflow padding">
													<div class="izquierda">
														<div><span>Proyecto : </span><br /><?php echo $row->REQ_PROYECTO ? $row->PROYECTO : '-'; ?></div>
														<div><span>Nº Interno: </span><br /><?php echo $row->REQ_NRO_INTERNO ? $row->REQ_NRO_INTERNO : '-'; ?></div>
														<div><span>Tipo de requerimiento : </span><br /><?php echo $row->TIPO ? $row->TIPO : '-' ; ?></div>
														<?php //  ?>
														<?php  if( count( $row->REQ_ENCARGADOS ) ):?>
															<div><span>Responsable Módulos : </span><br />
															<?php  foreach( $row->REQ_ENCARGADOS as $responsable ):?>
																<?php echo $responsable->name.'<br />'; ?>
															<?php endforeach; ?>
															</div>
														<?php endif; ?>
														<?php  //  ?>
														<div><span>Clasificación : </span><br /><?php echo $row->CLASIFICACION ? $row->CLASIFICACION : '-';; ?></div>
														<?php /* ?><div><span>Estado : </span><br /><?php echo $row->ESTADO ? $row->ESTADO : '-' ; ?></div><?php */ ?>
														<?php /* ?><div><span>N DRU : </span><br /><?php echo $row->REQ_DRU ? $row->REQ_DRU : '-'; ?></div><?php */ ?>
													</div>
													<div class="derecha">
														<div><span>Gerencia : </span><br /><?php echo $row->GERENCIA ? $row->GERENCIA : '-'; ?></div>
														<?php  //  ?>
														<div><span>Fecha y Hora de ingreso : </span><br /><?php echo $row->REQ_FECHA_CREACION ? $row->FECHA_CREACION : '-';; ?></div>
														<?php  //  ?>
														<div><span>Fecha y Hora Aprobación : </span><br /><?php echo $row->REQ_FECHA_APRUEBA ? $row->FECHA_APRUEBA : '-'; ?></div>
														<div><span>Usuario Solicitante : </span><br /><?php  echo $row->USUARIO->get('name') ? $row->USUARIO->get('name') : '-'; ?></div>
														<div><span>Usuario Aprobador : </span><br /><?php echo $row->REQ_USUARIO_APRUEBA ? $row->USUARIO_APRUEBA->get('name') : '-'; ?></div>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td class="gpti_objetivos">
												<div><span>Fecha y Hora Entrega : </span><br /><?php echo $row->REQ_FECHA_ENTREGA ? $row->FECHA_ENTREGA : '-'; ?></div>
												<?php  /*  ?>
												<div><span>Objetivos : </span><br /><?php echo $row->REQ_OBJETIVO ? $row->REQ_OBJETIVO : '-'; ?></div>
												<div><span>Descripción : </span><br /><?php echo $row->REQ_DESCRIPCION ? $row->REQ_DESCRIPCION : '-'; ?></div>
												<?php  */  ?>
												<div><span>Proposito : </span><br /><?php echo $row->REQ_PROPOSITO ? $row->REQ_PROPOSITO : '-'; ?></div>
												<div><span>Diagnóstico : </span><br /><?php echo $row->REQ_DIAGNOSTICO ? $row->REQ_DIAGNOSTICO : '-'; ?></div>
												<div><span>Capacidades : </span><br /><?php echo $row->REQ_CAPACIDADES ? $row->REQ_CAPACIDADES : '-'; ?></div>
												<?php  if( count( $row->REQ_ANEXOS ) ):?>
													<div><span>Anexos : </span><br />
													<?php  foreach( $row->REQ_ANEXOS as $anexo ):?>
														<?php echo $anexo->ANX_LINK.'<br />'; ?>
													<?php endforeach; ?>
													</div>
												<?php endif; ?>
											</td>
										</tr>  		  
										<tr>
											<td class="gpti_detalle">
												<div class="gpti_overflow padding">
													<div class="ancho_x float"><span>Indice de decisión : </span><br /><?php echo $row->INDICE_DECISION && $row->REQ_FASE > 1 ? $row->INDICE_DECISION : '-'; ?></div>
													
													<div class="ancho_x float"><span>Prioridad : </span><br /><?php echo $row->REQ_PRIORIDAD ? $row->REQ_PRIORIDAD : '-';; ?></div>
													<?php if(GPTIHelperACL::check('req_derivar') && $row->REQ_FASE >= 3 && !$row->REQ_PROVEEDOR && (( $row->REQ_ESTADO == '1') || ($row->REQ_ESTADO == '5') || ($row->REQ_ESTADO == '2')) ): ?>
														<?php if( $row->REQ_PROVEEDOR ): ?>
															<div class="ancho_x float"><span>Asignacion proveedor : </span><br /><?php echo $row->REQ_PROVEEDOR ? $row->PROVEEDOR : '-';; ?></div>
														<?php else: ?>
															<div><?php echo $lists['select-proveedor']; ?></div>
														<?php endif; ?>
													<?php else: ?>
														<?php if( $row->REQ_PROVEEDOR ): ?>
															<div class="ancho_x float"><span>Asignacion proveedor : </span><br /><?php echo $row->REQ_PROVEEDOR ? $row->PROVEEDOR : '-';; ?></div>
														<?php endif; ?>
													<?php endif; ?>
												</div>
											</td>
										</tr>
										<tr>
											<td class="gpti_listado">
												<?php if( count($row->REQ_MODULOS)): ?>
												<div class="padding">
													<h2>Módulos Que Afectan</h2>
													<ul class="lista">
														<?php foreach( $row->REQ_MODULOS as $modulo ): ?>
															<li><?php echo $modulo->MOD_NOMBRE; ?></li>
														<?php endforeach; ?>
													</ul>
												</div>
												<?php endif; ?>											
												<?php if( count($row->REQ_AREAS)): ?>
												<div class="padding">
													<h2>Areas de Desarrollo de Soluciones</h2>
													<ul class="lista">
														<?php foreach( $row->REQ_AREAS as $area ): ?>
															<li><?php echo $area->ARE_NOMBRE; ?></li>
														<?php endforeach; ?>
													</ul>
												</div>
												<?php endif; ?>											
												<?php if( count($row->REQ_VALORES)): ?>
												<div class="padding">
													<h2>Dimenciones de Valor Soluciones</h2>
													<ul class="lista">
														<?php foreach( $row->REQ_VALORES as $valor ): ?>
															<li><?php echo $valor->VAS_NOMBRE; ?></li>
														<?php endforeach; ?>
													</ul>
												</div>
												<?php endif; ?>	
											</td>
										</tr>
										<tr>
											<td class="gpti_tareas">
												<?php if( count($row->REQ_TAREAS)): ?>
													<div class="scroll">
														<?php  foreach( $row->REQ_TAREAS as $tarea ): ?>
															<?php if( $tarea->TAR_RECURSO == $lists['recurso-proveedor'] ): ?>
																<div class="gpti_overflow padding">	
																	<div class="ancho_xl float"><span>Nombre de la Tarea : </span><br /><?php echo  $tarea->TAR_NOMBRE ? $tarea->TAR_NOMBRE : '-'; ?></div>
																	<div class="ancho_xl float"><span>HH estimadas : </span><br /><?php echo  $tarea->TAR_HH_ESTIMADA ? $tarea->TAR_HH_ESTIMADA : '-'; ?></div>
																	<div class="ancho_xl float"><span>Ejecutor : </span><br /><?php echo $tarea->TAR_RECURSO ? $tarea->RECURSO->name : '-'; ?></div>
																	<div class="ancho_xl float"><span>Tipo Tarea: </span><br /><?php echo $tarea->TAR_TIPO ? $tarea->TAT_NOMBRE : '-'; ?></div>
																	<div class="ancho_xl float"><span>Fecha inicio : </span><br /><?php echo $tarea->TAR_FECHA_INICIO ? $tarea->FECHA_INICIO : '-'; ?></div>
																	<div class="ancho_xl float"><span>Fecha Termino : </span><br /><?php echo $tarea->TAR_FECHA_TERMINO ? $tarea->FECHA_TERMINO : '-'; ?></div>  
																	<div><span>Observaciones : </span><br /><?php echo $tarea->TAR_OBSERVACIONES ? $tarea->TAR_OBSERVACIONES : '-'; ?></div>
																	<?php if( $tarea->TAR_HH_INFORMADA ): ?>
																		<div><span>HH informadas : </span><br /><?php echo $tarea->TAR_HH_INFORMADA ? $tarea->TAR_HH_INFORMADA : '-'; ?></div>
																		<div class="ancho_xl float"><span>Fecha inicio Real : </span><br /><?php echo $tarea->FECHA_INICIO_REAL ? $tarea->FECHA_INICIO_REAL : '-'; ?></div>
																		<div class="ancho_xl float"><span>Fecha Termino Real : </span><br /><?php echo $tarea->FECHA_TERMINO_REAL ? $tarea->FECHA_TERMINO_REAL : '-'; ?></div>  
																		<div><span>Observaciones del Ejecutor : </span><br /><?php echo $tarea->TAR_OBS_EJECUTOR ? $tarea->TAR_OBS_EJECUTOR : '-'; ?></div>
																	<?php endif; ?>		
																	<?php if( $lists['ACL_req_informar'] && !$tarea->TAR_HH_INFORMADA && $row->REQ_FASE > 3 && ( $row->REQ_ESTADO == 12 || $row->REQ_ESTADO == 13 ) ): ?>
																		<div class="gpti_boton">
																			<a href="<?php echo JRoute::_('index.php?c=tareas&task=informar&TAR_ID='.$tarea->TAR_ID );?>" title="Informar">Informar</a>
																		</div>
																	<?php endif; ?>	
																</div>
																<?php endif;
																if ( $lists['perfil-Gerente-Proveedor'] || $lists['perfil-Gerencia'] || $lists['perfil-Admin'] ) :?>
																<div class="gpti_overflow padding">		
																	<div class="ancho_xl float"><span>Nombre de la Tarea : </span><br /><?php echo $tarea->TAR_NOMBRE ? $tarea->TAR_NOMBRE : '-'; ?></div>
																	<div class="ancho_xl float"><span>HH estimadas : </span><br /><?php echo $tarea->TAR_HH_ESTIMADA ? $tarea->TAR_HH_ESTIMADA : '-'; ?></div>
																	<div class="ancho_xl float"><span>Ejecutor : </span><br /><?php echo $tarea->TAR_RECURSO ? $tarea->RECURSO->name : '-'; ?></div>
																	<div class="ancho_xl float"><span>Tipo Tarea: </span><br /><?php echo $tarea->TAR_TIPO ? $tarea->TAT_NOMBRE : '-'; ?></div>
																	<div class="ancho_xl float"><span>Fecha inicio : </span><br /><?php echo $tarea->TAR_FECHA_INICIO ? $tarea->FECHA_INICIO : '-'; ?></div>
																	<div class="ancho_xl float"><span>Fecha Termino : </span><br /><?php echo $tarea->TAR_FECHA_TERMINO ? $tarea->FECHA_TERMINO : '-'; ?></div>  
																	<div><span>Observaciones : </span><br /><?php echo $tarea->TAR_OBSERVACIONES ? $tarea->TAR_OBSERVACIONES : '-'; ?></div>
																	<?php if( $tarea->TAR_HH_INFORMADA ): ?>
																		<div><span>HH informadas : </span><br /><?php echo $tarea->TAR_HH_INFORMADA ? $tarea->TAR_HH_INFORMADA : '-'; ?></div>
																		<div class="ancho_xl float"><span>Fecha inicio Real : </span><br /><?php echo $tarea->FECHA_INICIO_REAL ? $tarea->FECHA_INICIO_REAL : '-'; ?></div>
																		<div class="ancho_xl float"><span>Fecha Termino Real : </span><br /><?php echo $tarea->FECHA_TERMINO_REAL ? $tarea->FECHA_TERMINO_REAL : '-'; ?></div>  
																		<div><span>Observaciones del Ejecutor : </span><br /><?php echo $tarea->TAR_OBS_EJECUTOR ? $tarea->TAR_OBS_EJECUTOR : '-'; ?></div>
																	<?php endif; ?>	
																	<?php if( $lists['ACL_req_informar'] && !$tarea->TAR_HH_INFORMADA && $row->REQ_FASE > 3 && ( $row->REQ_ESTADO == 12 || $row->REQ_ESTADO == 13 ) ): ?>
																		<div class="gpti_boton">
																			<a href="<?php echo JRoute::_('index.php?c=tareas&task=informar&TAR_ID='.$tarea->TAR_ID );?>" title="Informar">Informar</a>
																		</div>
																	<?php endif; ?>	
																</div>
															<?php endif;
														endforeach; ?>
													</div>
												<?php endif;  ?>
											</td>
										</tr>
										<?php if( ( ( GPTIHelperACL::check('req_prueba_aprueba') || GPTIHelperACL::check('req_prueba') || GPTIHelperACL::check('req_programa_aprobar') ) && $row->REQ_FASE > 3 && ( $row->REQ_ESTADO == 7 || $row->REQ_ESTADO == 10 || $row->REQ_ESTADO == 11 ) ) || ( $lists['perfil-Gerencia'] && ( $row->REQ_FASE < 2 ) && (( $row->REQ_ESTADO == 2)) ) ) : ?>
										<tr>
											<td class="gpti_objetivos">
												<div><span>Observaciones : </span><br /><?php echo $lists['textarea-observacion']; ?></div>
											</td>
										</tr>  
										<?php endif;  ?>		  
										<tr>
											<td>
											
											<?php if( GPTIHelperACL::check('req_cerrar') && $row->REQ_FASE  > 4 && $row->REQ_ESTADO == 13 ) : ?>
												<div class="btn_ancho_s gpti_boton">
													<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmRequerimiento','cerrar'); return false;" title="Cerrar">Cerrar</a>
												</div>
											<?php endif; ?>
											<?php /*if( GPTIHelperACL::check('req_cerrar_proveedor') && ( $row->REQ_FASE > 4 ) && ( $row->REQ_ESTADO == '14' ) ) : ?>
												<div class="gpti_boton">
													<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmRequerimiento','cerrar_proveedor'); return false;" title="Cerrar Proveedor">Cerrar Proveedor</a>
												</div>
											<?php endif;*/ ?>
											<?php if( GPTIHelperACL::check('req_prueba') && ( $row->REQ_FASE > 3 ) && (( $row->REQ_ESTADO == 9 ) || ( $row->REQ_ESTADO == 11))  ) : ?>
												<div class="gpti_boton">
													<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmRequerimiento','pasaraprueba'); return false;" title="Pasar a Prueba">Pasar a Prueba</a>
												</div>
											<?php endif; ?>
											
											<?php if( GPTIHelperACL::check('req_programa_aprobar') && ( $row->REQ_FASE > 3 ) && ( $row->REQ_ESTADO == 7 ) ) : ?>
												<div class="gpti_boton btn_ancho">
													<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmRequerimiento','tarea_aprueba'); return false;" title="Aceptar Programación">Aceptar Programación</a>
												</div>
												<div class="gpti_boton btn_ancho">
													<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmRequerimiento','tarea_rechaza'); return false;" title="Rechazar Programación">Rechazar Programación</a>
												</div>
											<?php endif; ?>	
											<?php if( $lists['perfil-Gerencia'] && ( $row->REQ_FASE < 2 ) && (( $row->REQ_ESTADO == 2)) ) : ?>
												<div class="gpti_boton btn_ancho">
													<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmRequerimiento','acepta_ger'); return false;" title="Aceptar Requerimiento">Aceptar Requerimiento</a>
												</div>
												<div class="gpti_boton btn_ancho">
													<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmRequerimiento','rechaza_ger'); return false;" title="Rechazar Requerimiento">Rechazar Requerimiento</a>
												</div>
                                                <div class="gpti_boton btn_ancho">
													<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmRequerimiento','anular_ger'); return false;" title="Anular Requerimiento">Anular Requerimiento</a>
												</div>
											<?php endif; ?>													
											<?php if( GPTIHelperACL::check('req_programar') && ( $row->REQ_FASE > 3 ) && (( $row->REQ_ESTADO == 6 ) || ( $row->REQ_ESTADO == 8)) ) : ?>
												<div class="gpti_boton">
													<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmRequerimiento','planificar'); return false;" title="Programar">Programar</a>
												</div>
											<?php endif; ?>			
																			
											<?php if( GPTIHelperACL::check('req_prueba_aprueba') && ( $row->REQ_FASE > 4 ) && ( $lists['perfil-Admin'] || $lists['perfil-Gerencia'] || ($lists['id-Usuario'] == $row->REQ_USUARIO) ) && ( $row->REQ_ESTADO == 10 ) ) : ?>
												<div class="gpti_boton">
													<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmRequerimiento','aceptar_prueba'); return false;" title="Aceptar Prueba">Aceptar Prueba</a>
												</div>
												<div class="gpti_boton">
													<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmRequerimiento','rechaza_prueba'); return false;" title="Rechaza Prueba">Rechaza Prueba</a>
												</div>
											<?php endif; ?>
											
											<?php if(GPTIHelperACL::check('req_derivar') && $row->REQ_FASE >= 3 && !$row->REQ_PROVEEDOR && $row->REQ_ESTADO == 5 ): ?>
												<div class="btn_ancho gpti_boton">
													<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmRequerimiento','ingresar_proveedor'); return false;" title="Asignar proveedor">Asignar proveedor</a>
												</div>
											<?php endif; ?>
											
											<?php if( GPTIHelperACL::check('req_ingresar') && ( $lists['perfil-Usuario-Fase'] || $lists['perfil-Gerencia-Fase'] ) && (( $row->REQ_ESTADO == 2 ) || ( $row->REQ_ESTADO == 3 )|| ( $row->REQ_ESTADO == 4 )) ) : ?>
												<div class="gpti_boton">
													<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmRequerimiento','editar'); return false;" title="Modificar">Modificar</a>
												</div>
											<?php endif; ?>
											
											<?php if( GPTIHelperACL::check('req_ingresar') && $lists['perfil-Admin-Fase'] && (( $row->REQ_ESTADO == 4 )|| ( $row->REQ_ESTADO == 5 ))):?>
											<div class="gpti_boton">
													<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmRequerimiento','editar'); return false;" title="Modificar">Modificar</a>
												</div>
											<?php endif; ?>

												<div class="gpti_boton off">
													<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmRequerimiento','display'); return false;" title="Volver">Volver</a>
												</div>
											</td>
										</tr>
									</table>
								<input type="hidden" name="option" value="com_gpti" />
								<input type="hidden" name="c" value="requerimientos" />
								<input type="hidden" name="task" value="" />
								<input type="hidden" name="REQ_ID" value="<?php echo $row->REQ_ID;?>" />
								<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
								</form>
							</div>
							<div class="gpti_derecha gpti_float_l" align="right">
								<?php GPTIVistaGeneral::listas_derecha();?>
							</div>
						</td>
                    </tr>
               	</table>
            </div>

    	<?php
	}

	function ingresar( &$row, &$lists )
	{
		$session	=& JFactory::getSession();
		$GPTIuser	=& $session->get( 'GPTI_user', null );
		global $Itemid;
		//GPTI_ASSETS_URL
		?>
        	<a name="top"></a>
            <div class="gpti_ancho">
				<?php if( !$lists['req-id'] ): ?>
            	<div class="gpti_titulo"><h1>Ingresar Requerimiento</h1></div>
				<?php else: ?>
            	<div class="gpti_titulo">
                	<h1>Editar Requerimiento</h1>
                    <h2>[ <?php echo $row->REQ_DRU; ?> ] <?php echo $row->REQ_NOMBRE; ?></h2>
                </div>
				<?php endif; ?>
                <table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_tabla">
            		<tr>
						<td>
							<div class="gpti_centro_xxxl gpti_float_l">
								<form name="frmReqIngresar" id="frmReqIngresar" action="<?php echo JRoute::_("index.php?option=com_gpti&Itemid=$Itemid");?>" method="post" enctype="multipart/form-data">
									<?php if( $lists['msj'] ): ?>
										<div id="gpti_msj" class="gpti_msj" align="left"><ul class="incorrecto"><li><?php echo $lists['msj']; ?></li></ul></div>
									<?php else: ?>
										<div id="gpti_msj" class="gpti_msj" align="left"></div>
									<?php endif; ?>
									<table cellpadding="0" cellspacing="0" border="0" class="gpti_ingresodru" width="100%">
										<tr>
											<td class="gpti_proyecto">
												<div><?php echo $lists['select-proyecto']; ?></div>
												<div class="padding"><?php echo $lists['text-nombre']; ?></strong>
													<?php if( GPTIHelperACL::check('req_ingresar_ext_2') ) : 
														echo $lists['select-tipo'];
													endif; ?>
												</div>
												<?php if( GPTIHelperACL::check('req_ingresar_ext_2') ) : ?>
													<div class="padding"><?php echo $lists['select-clasificacion'] . $lists['select-gerencia']; ?></div>
												<?php endif; ?>
											</td>
										</tr>
										<tr>
											<td class="gpti_objetivos">
												<div><?php echo $lists['calendario-fecha-entrega'];?></div>
												<?php  /*  ?>
												<div><?php echo $lists['textarea-objetivo'];?></div>
												<div><?php echo $lists['textarea-desc'];?></div>
												<?php  */  ?>
												<div><?php echo $lists['textarea-proposito'] . $lists['info-proposito'];?></div>
												<div><?php echo $lists['textarea-diagnostico'] . $lists['info-diagnostico'];?></div>
												<div><?php echo $lists['textarea-capacidades'] . $lists['info-capacidades'];?></div>
											</td>
										</tr>
										<?php /* if( GPTIHelperACL::check('req_ingresar_ext_2') ) : ?>
										<tr>
											<td class="gpti_objetivos">
												<div><?php echo $lists['select-tipo'] .  $lists['select-gerencia']; ?></div>
												<div><?php echo $lists['select-clasificacion']; ?></div>
											</td>
										</tr>
										<?php endif; */ ?>  
										<tr>
											<td class="gpti_informacion">
												<h2>Adjunte Información</h2>												
												<div id="inputfiles">
												
													<?php  if( count( $lists['archivos-anexos'] ) ):?>
														<?php  foreach( $lists['archivos-anexos'] as $anexo ):?>
															<div class="anexo"><?php echo $anexo->ANX_LINK; ?></div>
														<?php endforeach; ?>
													<?php endif; ?>
																								
													<div class="gpti_overflow inputfile" id="input0" >
														<div id="cont0">
															<div id="mas0">
																<a href="javascript:void(0);" onclick="javascript:GPTI_Req_Anexos_agregar(0); return false;" title="+"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/mas.jpg" alt="" width="23" height="23" /></a>
															</div>
														</div>
														<div><?php echo $lists['select-file'];?></div>
													</div>
												</div>
												<?php /* ?>
												<div class="gpti_overflow proyecto">
													<div><?php echo $lists['select-prioridad'];?></div>
												</div> 
												<?php */ ?>
											</td>
										</tr>
										<tr>
											<td class="gpti_select">
												<h2>Módulos que Afectan <strong><small>(*)</small></strong></h2>
												<div class="gpti_overflow borde"><?php echo $lists['multiple-modulos'];?></div>
											<?php
											if( GPTIHelperACL::check('req_ingresar_ext_1') ) : ?>
												<h2>Areas de Desarrollo de Soluciones <strong><small>(*)</small></strong></h2>
												<div class="gpti_overflow borde"><?php echo $lists['multiple-areas'];?></div>
												<div class="gpti_overflow borde indice"><div align="center"><?php echo $lists['boton-indice'];?></div></div>                                    	
												<h2>Dimensiones de Valor de Soluciones <strong><small>(*)</small></strong></h2>
												<div class="gpti_overflow borde"><?php echo $lists['multiple-valores'];?></div> 
											<?php endif; ?>                                 
											</td>
										</tr>
										<tr>
											<td>
												<?php if( GPTIHelperACL::check('req_ingresar') ) : ?>											
													<div class="gpti_boton">
														<?php if( $lists['req-id'] ): ?>
															<a href="javascript:void(0);" onclick="javascript:GPTI_Req_Editar(); return false;" title="Ingresar Edición">Aplicar</a>
														<?php else:?>
															<a href="javascript:void(0);" onclick="javascript:GPTI_Req_Ingresar(); return false;" title="Ingresar">Ingresar</a>
														<?php endif;?>
													</div>
												<?php endif; ?>
												<div class="gpti_boton off">
													<a href="javascript:void(0);" onclick="javascript:document.frmReqIngresar.reset(); return false;" title="Limpiar">Limpiar</a>
												</div>
											</td>
										</tr>
									</table>
									<input type="hidden" name="option" value="com_gpti" />
									<input type="hidden" name="c" value="requerimientos" />
									<input type="hidden" name="task" value="" />
									<input type="hidden" name="REQ_ID" value="<?php echo $lists['req-id']; ?>" />
									<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
								</form>
							</div>
                        </td>
                    </tr>
               	</table>
            </div>
    	<?php
	}

	function  planificar( &$lists , $row )
	{
		global $Itemid;
		//GPTI_ASSETS_URL
		?>
		<script type="text/javascript">
			function validarTarea()
			{
				form = document.frmTarea;
				var nm = 0;
				var hh = 0;
				var rc = 0;
				var tp = 0;
				var fi = 0;
				var ft = 0;
				
				error = ' Los siguientes campos estan incompletos : \n ' ;
				for( var i=0; i<form.elements.length; i++ )
				{
					switch( form.elements[i].name )
					{
						case 'TAR_NOMBRE[]' :
							nm = nm + 1;
							if( form.elements[i].value == 'Nombre de la Tarea' )
							{
								error += '- Nombre de la Tarea N°' + nm + ' \n ';
							}						
						break;
						case 'TAR_HH_ESTIMADA[]' :
							hh = hh + 1;
							if( form.elements[i].value == 'Estimación HH' )
							{
								error += '- Estimación HH de la Tarea N°' + hh + ' \n '
							}						
						break;
						case 'TAR_RECURSO[]' :
							rc = rc + 1;
							if( form.elements[i].selectedIndex  == 0 )
							{
								error += '- Ejecutor de la Tarea N°' + rc + ' \n '
							}						
						break;
						case 'TAR_TIPO[]' :
							tp = tp + 1;
							if( form.elements[i].selectedIndex == 0 )
							{
								error += '- Tipo de la Tarea N°' + tp + ' \n '
							}						
						break;
						case 'TAR_FECHA_INICIO[]' :
							fi = fi + 1;
							if( form.elements[i].value == 'Fecha Inicio' )
							{
								error += '- Fecha de Inicio de la Tarea N°' + fi + ' \n '
							}						
						break;
						case 'TAR_FECHA_TERMINO[]' :
							ft = ft + 1;
							if( form.elements[i].value == 'Fecha Termino' )
							{
								error += '- Fecha de Termino de la Tarea N°' + ft + ' \n '
							}
						break;
					}
				}
				if( error != ' Los siguientes campos estan incompletos : \n ' )
				{
					alert( error );
					return false;
				}
				return true;
			}
			
			function GPTI_agregarTarea( i )
			{
				if( validarTarea() )
				{
					var o = i + 1;				
					
					jQuery('<div class="gpti_overflow padding" id="tarea'+o+'"><div class="float mas" id="tareacont'+o+'"><div id="mas'+o+'"><a href="javascript:void(0);" onclick="javascript:GPTI_agregarTarea('+o+'); return false;" title="+"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/mas.jpg" alt="" width="23" height="23" /></a><a href="javascript:void(0);" onclick="javascript:GPTI_eliminarTarea('+o+'); return false;" title="&nbsp;-&nbsp;"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/menos.jpg" alt="" width="23" height="23" /></a></div></div><div class="ancho_xl float"><?php echo $lists['text-nombre']; ?></div><div class="ancho_xl float"><?php echo $lists['text-estimada-hh']; ?></div><div class="ancho_xl float"><?php echo $lists['select-ejecutor']; ?></div><div class="ancho_xl float"><?php echo $lists['select-tipo']; ?></div><div class="ancho_xl float"><input type="text" name="TAR_FECHA_INICIO[]" onfocus="javascript:form_texto_focus(this);" onblur="javascript:form_texto_blur(this);" id="TAR_FECHA_INICIO'+o+'" class="inputclass"  value="Fecha Inicio" size="" /><img id="TAR_FECHA_INICIO'+o+'_img" src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/calendar.gif" width="18" height="17" alt="" /></div><div class="ancho_xl float"><input type="text" id="TAR_FECHA_TERMINO'+o+'" onfocus="javascript:form_texto_focus(this);" onblur="javascript:form_texto_blur(this);" name="TAR_FECHA_TERMINO[]" class="inputclass"  value="Fecha Termino" size="" /><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/calendar.gif" width="18" height="17" alt="" id="TAR_FECHA_TERMINO'+o+'_img" /></div><div><?php echo $lists['textarea-observaciones']; ?></div><input type="hidden" name="TAR_ID[]" value="" /></div>').animate({ height : 150 ,opacity : "show" }, "slow" ).appendTo('#tareas');
					jQuery( 'div#mas' + i ).animate({ opacity: "hide" }, "slow").remove();
					jQuery('<div id="mas'+i+'"><a href="javascript:void(0);" onclick="javascript:GPTI_eliminarTarea('+i+'); return false;" title="&nbsp;-&nbsp;"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/menos.jpg" alt="" width="23" height="23" /></a></div>').animate({ opacity: "show" }, "slow").appendTo('#tareacont'+i);
					jQuery('[name="ntareas"]').val( parseInt(jQuery('[name="ntareas"]').val()) + 1 );
	
					window.addEvent('domready', function(){ 
						Calendar.setup({ inputField:"TAR_FECHA_INICIO"+o,  ifFormat:"%Y-%m-%d", button:"TAR_FECHA_INICIO"+o+"_img",  align:"Tl", singleClick:true });
						Calendar.setup({ inputField:"TAR_FECHA_TERMINO"+o, ifFormat:"%Y-%m-%d", button:"TAR_FECHA_TERMINO"+o+"_img", align:"Tl", singleClick:true });
					});
				};
			}
		</script>
        	<a name="top"></a>
            <div class="gpti_ancho">
                <table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_tabla">
            		<tr>
						<td class="gpti_centro_xl">
							<div class="gpti_titulo">
                            	<h1>Planificación DRU</h1>
                                <h2>[ <?php echo $row->REQ_DRU; ?> ] <?php echo $row->REQ_NOMBRE; ?></h2>
                            </div>
                        </td>
                     	<td class="gpti_derecha" align="right">&nbsp;</td>
					</tr>	
				</table>
                <table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_tabla">
            		<tr>
						<td>
							<div class="gpti_izquierda gpti_float_l" align="center">
								<?php GPTIVistaGeneral::login();?>
							</div>
							<div class="gpti_centro gpti_float_l">
								<form action="<?php echo JRoute::_("index.php?option=com_gpti&Itemid=$Itemid");?>" method="post" name="frmTarea" id="frmTarea" enctype="multipart/form-data">
									<?php if( $lists['msj'] ): ?>
										<div id="gpti_msj" class="gpti_msj" align="left"><ul class="incorrecto"><li><?php echo $lists['msj']; ?></li></ul></div>
									<?php else: ?>
										<div id="gpti_msj" class="gpti_msj" align="left"></div>
									<?php endif; ?>
									<table cellpadding="0" cellspacing="0" border="0" class="gpti_cerrardru">
										<tr>
											<td class="gpti_detalle">
												<div class="gpti_overflow padding">
													<div class="izquierda">
														<div><span>Proyecto : </span><br /><?php echo $row->PROYECTO ? $row->PROYECTO : '-'; ?></div>
														<div><span>Nº Interno: </span><br /><input type="text" name="REQ_NRO_INTERNO" id="REQ_NRO_INTERNO" value="<?php echo $row->REQ_NRO_INTERNO ? $row->REQ_NRO_INTERNO : '-'; ?>" /></div>
														<div><span>Tipo de requerimiento : </span><br /><?php echo $row->TIPO ? $row->TIPO : '-' ; ?></div>
														<?php //  ?>
														<?php  if( count( $row->REQ_ENCARGADOS ) ):?>
															<div><span>Responsable Módulos : </span><br />
															<?php  foreach( $row->REQ_ENCARGADOS as $responsable ):?>
																<?php echo $responsable->name.'<br />'; ?>
															<?php endforeach; ?>
															</div>
														<?php endif; ?>
														<?php  //  ?>
														<div><span>Clasificación : </span><br /><?php echo $row->CLASIFICACION ? $row->CLASIFICACION : '-';; ?></div>
														<?php /* ?><div><span>Estado : </span><br /><?php echo $row->ESTADO ? $row->ESTADO : '-' ; ?></div><?php */ ?>
														<?php /* ?><div><span>N DRU : </span><br /><?php echo $row->REQ_DRU ? $row->REQ_DRU : '-'; ?></div><?php */ ?>
													</div>
													<div class="derecha">
														<div><span>Gerencia : </span><br /><?php echo $row->GERENCIA ? $row->GERENCIA : '-'; ?></div>
														<?php  //  ?>
														<div><span>Fecha y Hora de ingreso : </span><br /><?php echo $row->REQ_FECHA_CREACION ? $row->REQ_FECHA_CREACION : '-';; ?></div>
														<?php  //  ?>
														<div><span>Fecha y Hora Aprobación : </span><br /><?php echo $row->REQ_FECHA_APRUEBA ? $row->FECHA_APRUEBA : '-'; ?></div>
														<div><span>Usuario Solicitante : </span><br /><?php  echo $row->USUARIO->get('name') ? $row->USUARIO->get('name') : '-'; ?></div>
														<div><span>Usuario Aprobador : </span><br /><?php echo $row->REQ_USUARIO_APRUEBA ? $row->USUARIO_APRUEBA->get('name') : '-'; ?></div>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td class="gpti_objetivos">
												<div><span>Fecha y Hora Entrega : </span><br /><?php echo $row->REQ_FECHA_ENTREGA ? $row->FECHA_ENTREGA : '-'; ?></div>
												<?php  /*  ?>
												<div><span>Objetivos : </span><br /><?php echo $row->REQ_OBJETIVO ? $row->REQ_OBJETIVO : '-'; ?></div>
												<div><span>Descripción : </span><br /><?php echo $row->REQ_DESCRIPCION ? $row->REQ_DESCRIPCION : '-'; ?></div>
												<?php  */  ?>
												<div><span>Proposito : </span><br /><?php echo $row->REQ_PROPOSITO ? $row->REQ_PROPOSITO : '-'; ?></div>
												<div><span>Diagnóstico : </span><br /><?php echo $row->REQ_DIAGNOSTICO ? $row->REQ_DIAGNOSTICO : '-'; ?></div>
												<div><span>Capacidades : </span><br /><?php echo $row->REQ_CAPACIDADES ? $row->REQ_CAPACIDADES : '-'; ?></div>
												<?php  if( count( $row->REQ_ANEXOS ) ):?>
													<div><span>Anexos : </span><br />
													<?php  foreach( $row->REQ_ANEXOS as $anexo ):?>
														<?php echo $anexo->ANX_LINK.'<br />'; ?>
													<?php endforeach; ?>
													</div>
												<?php endif; ?>
											</td>
										</tr>  		  
										<tr>
											<td class="gpti_detalle">
												<div class="gpti_overflow padding">
													<div class="ancho_x float"><span>Indice de decisión : </span><br /><?php echo $row->INDICE_DECISION ? $row->INDICE_DECISION : '-';; ?></div>
													
													<div class="ancho_x float"><span>Prioridad : </span><br /><?php echo $row->REQ_PRIORIDAD ? $row->REQ_PRIORIDAD : '-';; ?></div>
													<?php if(GPTIHelperACL::check('req_derivar') && $row->REQ_FASE >= 3 && !$row->REQ_PROVEEDOR && (( $row->REQ_ESTADO == '1') || ($row->REQ_ESTADO == '5') || ($row->REQ_ESTADO == '2')) ): ?>
														<?php if( $row->REQ_PROVEEDOR ): ?>
															<div class="ancho_x float"><span>Asignacion proveedor : </span><br /><?php echo $row->REQ_PROVEEDOR ? $row->PROVEEDOR : '-';; ?></div>
														<?php else: ?>
															<div><?php echo $lists['select-proveedor']; ?></div>
														<?php endif; ?>
													<?php else: ?>
														<?php if( $row->REQ_PROVEEDOR ): ?>
															<div class="ancho_x float"><span>Asignacion proveedor : </span><br /><?php echo $row->REQ_PROVEEDOR ? $row->PROVEEDOR : '-';; ?></div>
														<?php endif; ?>
													<?php endif; ?>
												</div>
											</td>
										</tr>
										<tr>
											<td class="gpti_listado">
												<?php if( count($row->REQ_MODULOS)): ?>
												<div class="padding">
													<h2>Módulos Que Afectan</h2>
													<ul class="lista">
														<?php foreach( $row->REQ_MODULOS as $modulo ): ?>
															<li><?php echo $modulo->MOD_NOMBRE; ?></li>
														<?php endforeach; ?>
													</ul>
												</div>
												<?php endif; ?>											
												<?php if( count($row->REQ_AREAS)): ?>
												<div class="padding">
													<h2>Areas de Desarrollo de Soluciones</h2>
													<ul class="lista">
														<?php foreach( $row->REQ_AREAS as $area ): ?>
															<li><?php echo $area->ARE_NOMBRE; ?></li>
														<?php endforeach; ?>
													</ul>
												</div>
												<?php endif; ?>											
												<?php if( count($row->REQ_VALORES)): ?>
												<div class="padding">
													<h2>Dimenciones de Valor Soluciones</h2>
													<ul class="lista">
														<?php foreach( $row->REQ_VALORES as $valor ): ?>
															<li><?php echo $valor->VAS_NOMBRE; ?></li>
														<?php endforeach; ?>
													</ul>
												</div>
												<?php endif; ?>	
											</td>
										</tr>										
										<tr>
											<td class="gpti_tareas" id="tareas">
													<?php
													$i = 0;
													if( !count($row->REQ_TAREAS)): ?>
															<div class="gpti_overflow padding" id="tarea<?php echo $i; ?>">												
																<div  class="float mas" id="tareacont<?php echo $i; ?>">
																	<div id="mas<?php echo $i; ?>">
																		 <a href="javascript:void(0);" onclick="javascript:GPTI_agregarTarea(<?php echo $i; ?>); return false;" title="+">
																			<img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/mas.jpg" alt="" width="23" height="23" />
																		 </a>
																	</div>
																</div>
																<div class="ancho_xl float"><?php echo $lists['text-nombre']; ?></div>
																<div class="ancho_xl float"><?php echo $lists['text-estimada-hh']; ?></div>
																<div class="ancho_xl float"><?php echo $lists['select-ejecutor']; ?></div>
																<div class="ancho_xl float"><?php echo $lists['select-tipo']; ?></div>
																<div class="ancho_xl float"><?php echo $lists['calendario-fecha-inicio']; ?></div>
																<div class="ancho_xl float"><?php echo $lists['calendario-fecha-termino']; ?></div>  
																<div><?php echo $lists['textarea-observaciones']; ?></div>
																<input type="hidden" name="TAR_ID[]" value="<?php echo $TAR->TAR_ID; ?>" />
															</div>																									
													<?php else: ?>
														<?php if( $lists['msj'] ): ?>
															<div id="gpti_msj" class="gpti_msj"><ul class="incorrecto"><li><?php echo $lists['msj']; ?></li></ul></div>
														<?php endif; ?>
														<?php $i++; ?>
														<?php foreach( $row->REQ_TAREAS as $e => $TAR ): ?>
															<div class="gpti_overflow padding" id="tarea<?php echo $i + $e; ?>">												
																<?php /* ?>
																<div  class="float mas" id="tareacont<?php echo $i + $e; ?>">
																	<div id="mas<?php echo $i + $e; ?>">
																		 <a href="javascript:void(0);" onclick="javascript:GPTI_eliminarTarea(<?php echo $i + $e; ?>); return false;" title="-">
																			<img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/menos.jpg" alt="" width="23" height="23" />
																		 </a>
																	</div>
																</div>
																<?php */ ?>
																<div class="ancho_xl float"><?php echo $lists['text-nombre-arreglo'][$e]; ?></div>
																<div class="ancho_xl float"><?php echo $lists['text-estimada-hh-arreglo'][$e]; ?></div>
																<div class="ancho_xl float"><?php echo $lists['select-ejecutor-arreglo'][$e]; ?></div>
																<div class="ancho_xl float"><?php echo $lists['select-tipo-arreglo'][$e]; ?></div>
																<div class="ancho_xl float"><?php echo $lists['calendario-fecha-inicio-arreglo'][$e]; ?></div>
																<div class="ancho_xl float"><?php echo $lists['calendario-fecha-termino-arreglo'][$e]; ?></div>  
																<div><?php echo $lists['textarea-observaciones-arreglo'][$e]; ?></div>
																<input type="hidden" name="TAR_ID[]" value="<?php echo $TAR->TAR_ID; ?>" />
															</div>																									
														<?php endforeach; ?>
														<div id="mas<?php echo $i + $e; ?>">
															 <a href="javascript:void(0);" onclick="javascript:GPTI_agregarTarea(<?php echo $i + $e; ?>); return false;" title="+">
																<img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/mas.jpg" alt="" width="23" height="23" />
															 </a>
														</div>
													<?php endif; ?>
												

											</td>
										</tr>
										<tr>
											<td>
											<?php if( GPTIHelperACL::check('req_programar') ) : ?>
												<div class="gpti_boton">
													<a href="javascript:void(0);" onclick="javascript:GPTI_Tarea_Ingresar('check_gerencia'); return false;" title="Ingresar">Ingresar</a>
												</div>
											<?php endif;?>
											
											<div class="gpti_boton off">
												<a href="javascript:void(0);" onclick="javascript:GPTI_Reset('frmTarea');" title="Limpiar">Limpiar</a>
											</div>

											</td>
										</tr>
									</table>
								<input type="hidden" id="ntareas" name="ntareas" value="<?php echo ( $e ? $e + 1 : 1 ); ?>" />
								<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
								<input type="hidden" name="option" value="com_gpti" />
								<input type="hidden" name="c" value="requerimientos" />
								<input type="hidden" name="task" value="<?php echo $lists['tarea-check']; ?>" />
								<input type="hidden" name="REQ_ID" value="<?php echo $row->REQ_ID;?>" />
								</form>
							</div>
							<div class="gpti_derecha gpti_float_l" align="right">
								<?php  GPTIVistaGeneral::listas_derecha();?>
							</div>
						</td>
                    </tr>
               	</table>
            </div>

    	<?php
	}
	
	function cambiosprioridad( &$lists )
	{
		global $Itemid;
		?>
			<div class="gpti_ancho">
				<div class="gpti_centro_xl">
					<form action="<?php echo JRoute::_("index.php?option=com_gpti&Itemid=$Itemid");?>" method="post" name="frmCP" id="frmCP">
					<?php if( $lists['msj'] ): ?>
                        <div id="gpti_msj" class="gpti_msj"><ul class="correcto"><li><?php echo $lists['msj']; ?></li></ul></div>
                    <?php endif; ?>
						<table cellpadding="0" cellspacing="0" border="0" class="gpti_cerrardru">
							<tr>
								<td class="gpti_detalle">			
									<div class="gpti_boton btn_ancho_x">
										<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmCP','cambiosprioridad_proveedor'); return false;" title="Prioridad Proveedores">Prioridad Proveedores</a>
									</div>
									<div class="gpti_boton btn_ancho_x">
										<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmCP','cambiosprioridad_gerencia'); return false;" title="Prioridad Gerencias">Prioridad Gerencias</a>
									</div>
								</td>
							</tr>     
							<tr>
								<td class="gpti_detalle">			
									<div class="gpti_boton btn_ancho_x" style="margin-right:145px;">
										<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmCP','cambiosprioridad_comite'); return false;" title="Prioridad Comité">Prioridad Comité</a>
									</div>
								</td>
							</tr>     
							<tr>
								<td class="gpti_detalle">&nbsp;</td>
							</tr>     
						</table>
					<input type="hidden" name="option" value="com_gpti" />
					<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
					<input type="hidden" name="c" value="requerimientos" />
					<input type="hidden" name="task" value="cambiosprioridad" />
					</form>
				</div>
				<br /><br />
			</div>
        <?php
	}

	function  cambiosprioridad_gerencia( &$lists, $rows, $row )
	{
		global $Itemid;
		//GPTI_ASSETS_URL
		?>
		<?php if( $lists['ACL_req_cp'] ) : ?>
		<script type="text/javascript" src="<?php JURI::base(); ?>components/com_gpti/assets/js/jquery.tablednd_0_5.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery("#gpti_resultados").tableDnD({ 
					onDragClass: "gpti_drag" ,
					onDrop: function(table, row) {
						var rows = table.tBodies[0].rows;
						var debugStr = "";
						for (var i=0, e=1; i<rows.length; i++, e++) 
						{
							jQuery('#'+rows[i].id +' strong').text( e );
							if( i % 2)
							{
								jQuery('#'+rows[i].id ).attr( "bgcolor","#EBEBEB" );
							}
							else
							{
								jQuery('#'+rows[i].id ).attr( "bgcolor","#FFFFFF" );
							}
						}
					}
				});
			}); 
		</script>
        <?php endif; ?>
            <div class="gpti_ancho">
            <?php if( $lists['ACL_req_cp_aprobar'] ) : ?>
				<div class="gpti_centro_xl">
					<form action="<?php echo JRoute::_("index.php?option=com_gpti&Itemid=$Itemid");?>" method="post" name="frmCP" id="frmCP">
						<table cellpadding="0" cellspacing="0" border="0" class="gpti_cerrardru">
							<tr>
								<td class="gpti_detalle">			
									<div class="gpti_boton btn_ancho_x">
										<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmCP','cambiosprioridad_proveedor'); return false;" title="Prioridad Proveedores">Prioridad Proveedores</a>
									</div>
									<div class="gpti_boton btn_ancho_x">
										<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmCP','cambiosprioridad_gerencia'); return false;" title="Prioridad Gerencias">Prioridad Gerencias</a>
									</div>
								</td>
							</tr>
							<tr>
								<td class="gpti_detalle">			
									<div class="gpti_boton btn_ancho_x" style="margin-right:145px;">
										<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmCP','cambiosprioridad_comite'); return false;" title="Prioridad Comité">Prioridad Comité</a>
									</div>
								</td>
							</tr>     
							<tr>
								<td class="gpti_detalle">&nbsp;</td>
							</tr>     
						</table>
                        <input type="hidden" name="option" value="com_gpti" />
                        <input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
                        <input type="hidden" name="c" value="requerimientos" />
                        <input type="hidden" name="task" value="cambiosprioridad" />
					</form>
				</div>
				<br /><br />
            <?php endif; ?>
                <table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_tabla">
            		<tr>
                     	<td class="gpti_centro_xl">
							<div class="gpti_titulo"><h1>Prioridad Gerencias</h1></div>
                        </td>
					</tr>	
				</table>
                <table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_tabla" id="gpti_tabla">
            		<tr>
						<td>
                     	<!--<div class="gpti_centro_xl gpti_float_l">-->
							<form action="<?php echo JRoute::_("index.php?option=com_gpti&Itemid=$Itemid");?>" method="post" name="frmPrioridad" id="frmPrioridad">
						<?php if( $lists['msj'] ): ?>
                            <div id="gpti_msj" class="gpti_msj"><?php echo $lists['msj']; ?></div>
                        <?php endif; ?>
                            <?php if( $lists['ACL_req_cp_aprobar'] /*&& is_array($rows) && count($rows)*/ ) : ?>
                            <div class="gpti_msj" style="padding:10px 0px;">
                            	<label for="RCP_ID">Gerencia :</label> <?php echo $lists['p_gerencia'];?>
                            </div>
                            <?php endif;?>
							<?php if( is_array($rows) && count($rows) ) :  ?>
             	   			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_filtro">
								<tr>
                                    <td width="10" align="left"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_izq_busqueda.jpg" width="10" height="10" alt="" /></td>
                                    <td></td>
                                    <td width="10" align="right"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_der_busqueda.jpg" width="10" height="10" alt="" /></td>
                                </tr>
								<tr>
                                    <td colspan="3">
                                    	<?php /*<input type="hidden" name="RCP_ID" value="<?php echo $row->RCP_ID;?>" />*/ ?>
                                        <table cellpadding="3" cellspacing="0" border="0" width="100%" class="gpti_resultados" id="gpti_resultados" summary="">
                                            <thead>
                                                <tr>
                                                    <th>Prioridad</th>
                                                    <th>Nº DRU</th>
                                                    <th>Responsable<br />Módulo</th>
                                                    <th>Estado</th>
                                                    <th>Proyecto</th>
                                                    <th>Nombre</th>
                                                    <th>Fecha de<br />Entrega</th>
                                                </tr>
                                         	</thead>
                                            <tbody>
												<?php foreach( $rows as $i => $row ) :  ?>
													<tr bgcolor="<?php echo ($i % 2)?'#EBEBEB':'#FFFFFF';?>" id="tr<?php echo $i; ?>">
														<td align="center"><strong><?php echo $row->REQ_PRIORIDAD < 10 ? '0'.$row->REQ_PRIORIDAD:$row->REQ_PRIORIDAD;?></strong><input type="hidden" name="cid[]" value="<?php echo $row->REQ_ID;?>" /></td>
														<td nowrap="nowrap"><a href="<?php echo $row->REQ_LINK;?>" title="Ver <?php echo $row->REQ_DRU; ?>"><?php echo $row->REQ_DRU; ?></a></td>
														<td nowrap="nowrap">
															<?php if( count($row->REQ_ENCARGADOS) ): ?>
																<?php $ir=0; foreach( $row->REQ_ENCARGADOS as $responsable ):?>
																	<?php echo ($ir++ ? ', ':'').$responsable->name; ?>
																<?php endforeach; ?>
															<?php endif; ?>
                                                        </td>
														<td><?php echo $row->RES_NOMBRE; ?></td>
														<td><?php echo $row->REQ_PROYECTO; ?></td>
														<td><?php echo $row->REQ_NOMBRE; ?></td>
														<td><?php echo $row->REQ_FECHA_ENTREGA; ?></td>
													</tr>
												<?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                	<td colspan="3" class="obs">
                                    	Observaciones:<br />
									  <?php if( $lists['ACL_req_cp'] ) : ?>
                                        <textarea name="RCP_OBS" id="RCP_OBS" rows="6" cols="50"></textarea>
                                      <?php endif;?>
                                      <?php if( $lists['ACL_req_cp_aprobar'] ) : ?>
                                      	<?php echo $rows[0]->RCP_OBS;?>
                                      
                                      	<br /><br />Respuesta:<br />
                                        <textarea name="RCP_OBS_ADMIN" id="RCP_OBS_ADMIN" rows="6" cols="50"></textarea>
                                      <?php endif;?>
                                    </td>
                                </tr>
								<tr>
                                    <td></td>
                                    <td>
                                      <?php if( $lists['ACL_req_cp'] ) : ?>
                                        <div class="btn_ancho gpti_boton">
                                           <a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmPrioridad','cambiosprioridad_submit'); return false;" title="Solicitar Cambio">Solicitar Cambio</a>
                                        </div>
                                      <?php endif;?>
                                      <?php if( $lists['ACL_req_cp_aprobar'] ) : ?>
                                        <div class="btn_ancho gpti_boton">
                                            <a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmPrioridad','cambiosprioridad_rechazar'); return false;" title="Rechazar">Rechazar</a>
                                        </div>
                                        <div class="btn_ancho gpti_boton">
                                            <a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmPrioridad','cambiosprioridad_aprobar'); return false;" title="Aprobar">Aprobar</a>
                                        </div>
                                      <?php endif;?>
                                    </td>
                                    <td></td>
                                </tr>
								<tr>
                                    <td align="left"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_izq_busqueda.jpg" width="10" height="10" alt="" /></td>
                                    <td></td>
                                    <td align="right"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_der_busqueda.jpg" width="10" height="10" alt="" /></td>
                                </tr>
                        	</table>                          
							<?php endif; ?>
                            <input type="hidden" name="option" value="com_gpti" />
                            <input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
                            <input type="hidden" name="task" value="" />
							</form>
                        <!--</div>-->

                     	<!--<div class="gpti_derecha gpti_float_l" align="right">
                            <?php  //GPTIVistaGeneral::listas_derecha();?>
                            <br /><br /><br />
                    		<?php  //GPTIVistaGeneral::login();?>
                        </div>-->
						</td>
                    </tr>
               	</table>
            </div>
    	<?php
	}

	function  cambiosprioridad_proveedor( &$lists, $rows, $row )
	{
		global $Itemid;
		//GPTI_ASSETS_URL
		?>
		<script type="text/javascript" src="<?php JURI::base(); ?>components/com_gpti/assets/js/jquery.tablednd_0_5.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery("#gpti_resultados").tableDnD({ 
					onDragClass: "gpti_drag" ,
					onDrop: function(table, row) {
						var rows = table.tBodies[0].rows;
						var debugStr = "";
						for (var i=0, e=1; i<rows.length; i++, e++) 
						{
							jQuery('#'+rows[i].id +' strong').text( e );
							if( i % 2)
							{
								jQuery('#'+rows[i].id ).attr( "bgcolor","#EBEBEB" );
							}
							else
							{
								jQuery('#'+rows[i].id ).attr( "bgcolor","#FFFFFF" );
							}
						}
					}
				});
			}); 
		</script>
            <div class="gpti_ancho">
				<div class="gpti_centro_xl">
					<form action="<?php echo JRoute::_("index.php?option=com_gpti&Itemid=$Itemid");?>" method="post" name="frmCP" id="frmCP">
					<?php if( $lists['msj'] ): ?>
                        <div id="gpti_msj" class="gpti_msj"><ul class="correcto"><li><?php echo $lists['msj']; ?></li></ul></div>
                    <?php endif; ?>
						<table cellpadding="0" cellspacing="0" border="0" class="gpti_cerrardru">
							<tr>
								<td class="gpti_detalle">			
									<div class="gpti_boton btn_ancho_x">
										<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmCP','cambiosprioridad_proveedor'); return false;" title="Prioridad Proveedores">Prioridad Proveedores</a>
									</div>
									<div class="gpti_boton btn_ancho_x">
										<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmCP','cambiosprioridad_gerencia'); return false;" title="Prioridad Gerencias">Prioridad Gerencias</a>
									</div>
								</td>
							</tr>
							<tr>
								<td class="gpti_detalle">			
									<div class="gpti_boton btn_ancho_x" style="margin-right:145px;">
										<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmCP','cambiosprioridad_comite'); return false;" title="Prioridad Comité">Prioridad Comité</a>
									</div>
								</td>
							</tr>     
							<tr>
								<td class="gpti_detalle">&nbsp;</td>
							</tr>     
						</table>
                        <input type="hidden" name="option" value="com_gpti" />
                        <input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
                        <input type="hidden" name="c" value="requerimientos" />
                        <input type="hidden" name="task" value="cambiosprioridad" />
					</form>
				</div>
				<br /><br />
                <table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_tabla">
            		<tr>
                     	<td class="gpti_centro_xl">
							<div class="gpti_titulo"><h1>Prioridad Proveedores</h1></div>
                        </td>
					</tr>	
				</table>
                <table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_tabla" id="gpti_tabla">
            		<tr>
						<td>
                     	<!--<div class="gpti_centro_xl gpti_float_l">-->
							<form action="<?php echo JRoute::_("index.php?option=com_gpti&Itemid=$Itemid");?>" method="post" name="frmPrioridad" id="frmPrioridad">
							<div id="gpti_msj" class="gpti_msj"><?php echo $lists['msj'];?></div>
                            <div class="gpti_msj" style="padding:10px 0px;">
                            	<label for="RCP_ID">Favor seleccionar el Proveedor :</label> <?php echo $lists['proveedores'];?>
                            </div>
							<?php if( is_array($rows) && count($rows) ) :  ?>
             	   			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_filtro">
								<tr>
                                    <td width="10" align="left"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_izq_busqueda.jpg" width="10" height="10" alt="" /></td>
                                    <td>&nbsp;</td>
                                    <td width="10" align="right"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_der_busqueda.jpg" width="10" height="10" alt="" /></td>
                                </tr>
								<tr>
                                    <td colspan="3">
                                    	<?php /*<input type="hidden" name="RCP_ID" value="<?php echo $row->RCP_ID;?>" />*/ ?>
                                        <table cellpadding="3" cellspacing="0" border="0" width="100%" class="gpti_resultados" id="gpti_resultados" summary="">
                                            <thead>
                                                <tr>
                                                    <th>Prioridad</th>
                                                    <th>Nº DRU</th>
                                                    <th>Nº Interno</th>
                                                    <th>Estado</th>
                                                    <th>Proyecto</th>
                                                    <th>Nombre</th>
                                                    <th>Fecha de<br />Entrega</th>
                                                </tr>
                                         	</thead>
                                            <tbody>
												<?php foreach( $rows as $i => $row ) :  ?>
													<tr bgcolor="<?php echo ($i % 2)?'#EBEBEB':'#FFFFFF';?>" id="tr<?php echo $i; ?>">
														<td align="center"><strong><?php echo $row->REQ_PRIORIDAD_PROV < 10 ? '0'.$row->REQ_PRIORIDAD_PROV:$row->REQ_PRIORIDAD_PROV;?></strong><input type="hidden" name="cid[]" value="<?php echo $row->REQ_ID;?>" /></td>
														<td nowrap="nowrap"><a href="<?php echo $row->REQ_LINK;?>" title="Ver <?php echo $row->REQ_DRU; ?>"><?php echo $row->REQ_DRU; ?></a></td>
														<td nowrap="nowrap"><?php echo $row->REQ_NRO_INTERNO; ?></td>
														<td><?php echo $row->RES_NOMBRE; ?></td>
														<td><?php echo $row->REQ_PROYECTO; ?></td>
														<td><?php echo $row->REQ_NOMBRE; ?></td>
														<td><?php echo $row->REQ_FECHA_ENTREGA; ?></td>
													</tr>
												<?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                	<td colspan="3" class="obs">
                                    	Observaciones:<br />
                                        <textarea name="RCP_OBS" id="RCP_OBS" rows="6" cols="50"></textarea>
                                    </td>
                                </tr>
								<tr>
                                    <td></td>
                                    <td>
                                        <div class="btn_ancho gpti_boton">
                                            <a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmPrioridad','cambiosprioridad_aprobar_prov'); return false;" title="Aplicar Cambio">Aplicar Cambio</a>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
								<tr>
                                    <td align="left"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_izq_busqueda.jpg" width="10" height="10" alt="" /></td>
                                    <td></td>
                                    <td align="right"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_der_busqueda.jpg" width="10" height="10" alt="" /></td>
                                </tr>
                        	</table>                          
							<?php endif; ?>
                            <input type="hidden" name="option" value="com_gpti" />
                            <input type="hidden" name="c" value="requerimientos" />
                            <input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
                            <input type="hidden" name="task" value="" />
							</form>
                        <!--</div>-->

                     	<!--<div class="gpti_derecha gpti_float_l" align="right">
                            <?php  //GPTIVistaGeneral::listas_derecha();?>
                            <br /><br /><br />
                    		<?php  //GPTIVistaGeneral::login();?>
                        </div>-->
						</td>
                    </tr>
               	</table>
            </div>
    	<?php
	}

	function  cambiosprioridad_comite( &$lists, $rows, $row )
	{
		global $Itemid;
		//GPTI_ASSETS_URL
		?>
		<script type="text/javascript" src="<?php JURI::base(); ?>components/com_gpti/assets/js/jquery.tablednd_0_5.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery("#gpti_resultados").tableDnD({ 
					onDragClass: "gpti_drag" ,
					onDrop: function(table, row) {
						var rows = table.tBodies[0].rows;
						var debugStr = "";
						for (var i=0, e=1; i<rows.length; i++, e++) 
						{
							jQuery('#'+rows[i].id +' strong').text( e );
							if( i % 2)
							{
								jQuery('#'+rows[i].id ).attr( "bgcolor","#EBEBEB" );
							}
							else
							{
								jQuery('#'+rows[i].id ).attr( "bgcolor","#FFFFFF" );
							}
						}
					}
				});
			}); 
		</script>
            <div class="gpti_ancho">
				<div class="gpti_centro_xl">
					<form action="<?php echo JRoute::_("index.php?option=com_gpti&Itemid=$Itemid");?>" method="post" name="frmCP" id="frmCP">
					<div id="gpti_msj" class="gpti_msj"></div>
						<table cellpadding="0" cellspacing="0" border="0" class="gpti_cerrardru">
							<tr>
								<td class="gpti_detalle">			
									<div class="gpti_boton btn_ancho_x">
										<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmCP','cambiosprioridad_proveedor'); return false;" title="Prioridad Proveedores">Prioridad Proveedores</a>
									</div>
									<div class="gpti_boton btn_ancho_x">
										<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmCP','cambiosprioridad_gerencia'); return false;" title="Prioridad Gerencias">Prioridad Gerencias</a>
									</div>
								</td>
							</tr>
							<tr>
								<td class="gpti_detalle">			
									<div class="gpti_boton btn_ancho_x" style="margin-right:145px;">
										<a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmCP','cambiosprioridad_comite'); return false;" title="Prioridad Comité">Prioridad Comité</a>
									</div>
								</td>
							</tr>     
							<tr>
								<td class="gpti_detalle">&nbsp;</td>
							</tr>     
						</table>
                        <input type="hidden" name="option" value="com_gpti" />
                        <input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
                        <input type="hidden" name="c" value="requerimientos" />
                        <input type="hidden" name="task" value="cambiosprioridad" />
					</form>
				</div>
				<br /><br />
                <table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_tabla">
            		<tr>
                     	<td class="gpti_centro_xl">
							<div class="gpti_titulo"><h1>Prioridad Comité</h1></div>
                        </td>
					</tr>	
				</table>
                <table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_tabla" id="gpti_tabla">
            		<tr>
						<td>
                     	<!--<div class="gpti_centro_xl gpti_float_l">-->
							<form action="<?php echo JRoute::_("index.php?option=com_gpti&Itemid=$Itemid");?>" method="post" name="frmPrioridad" id="frmPrioridad">
						<?php if( $lists['msj'] ): ?>
                            <div id="gpti_msj" class="gpti_msj"><ul class="correcto"><li><?php echo $lists['msj']; ?></li></ul></div>
                        <?php endif; ?>
							<?php if( is_array($rows) && count($rows) ) :  ?>
             	   			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="gpti_filtro">
								<tr>
                                    <td width="10" align="left"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_izq_busqueda.jpg" width="10" height="10" alt="" /></td>
                                    <td>&nbsp;</td>
                                    <td width="10" align="right"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_sup_der_busqueda.jpg" width="10" height="10" alt="" /></td>
                                </tr>
								<tr>
                                    <td colspan="3">
                                    	<?php /*<input type="hidden" name="RCP_ID" value="<?php echo $row->RCP_ID;?>" />*/ ?>
                                        <table cellpadding="3" cellspacing="0" border="0" width="100%" class="gpti_resultados" id="gpti_resultados" summary="">
                                            <thead>
                                                <tr>
                                                    <th>Prioridad</th>
                                                    <th>Nº DRU</th>
                                                    <th>Responsable<br />Módulo</th>
                                                    <th>Estado</th>
                                                    <th>Proyecto</th>
                                                    <th>Nombre</th>
                                                    <th>Fecha de<br />Entrega</th>
                                                </tr>
                                         	</thead>
                                            <tbody>
												<?php foreach( $rows as $i => $row ) :  ?>
													<tr bgcolor="<?php echo ($i % 2)?'#EBEBEB':'#FFFFFF';?>" id="tr<?php echo $i; ?>">
														<td align="center"><strong><?php echo $row->REQ_PRIORIDAD_CTE < 10 ? '0'.$row->REQ_PRIORIDAD_CTE:$row->REQ_PRIORIDAD_CTE;?></strong><input type="hidden" name="cid[]" value="<?php echo $row->REQ_ID;?>" /></td>
														<td nowrap="nowrap"><a href="<?php echo $row->REQ_LINK;?>" title="Ver <?php echo $row->REQ_DRU; ?>"><?php echo $row->REQ_DRU; ?></a></td>
														<td nowrap="nowrap">
															<?php if( count($row->REQ_ENCARGADOS) ): ?>
																<?php $ir=0; foreach( $row->REQ_ENCARGADOS as $responsable ):?>
																	<?php echo ($ir++ ? ', ':'').$responsable->name; ?>
																<?php endforeach; ?>
															<?php endif; ?>
                                                        </td>
														<td><?php echo $row->RES_NOMBRE; ?></td>
														<td><?php echo $row->REQ_PROYECTO; ?></td>
														<td><?php echo $row->REQ_NOMBRE; ?></td>
														<td><?php echo $row->REQ_FECHA_ENTREGA; ?></td>
													</tr>
												<?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                	<td colspan="3" class="obs">
                                    	Observaciones:<br />
                                        <textarea name="RCP_OBS" id="RCP_OBS" rows="6" cols="50"></textarea>
                                    </td>
                                </tr>
								<tr>
                                    <td></td>
                                    <td>
                                        <div class="btn_ancho gpti_boton">
                                            <a href="javascript:void(0);" onclick="javascript:GPTI_submit('frmPrioridad','cambiosprioridad_aprobar_cte'); return false;" title="Aplicar Cambio">Aplicar Cambio</a>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
								<tr>
                                    <td align="left"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_izq_busqueda.jpg" width="10" height="10" alt="" /></td>
                                    <td></td>
                                    <td align="right"><img src="<?php echo GPTI_TEMPLATE_URL;?>imagenes/esq_inf_der_busqueda.jpg" width="10" height="10" alt="" /></td>
                                </tr>
                        	</table>                          
							<?php endif; ?>
                            <input type="hidden" name="option" value="com_gpti" />
                            <input type="hidden" name="c" value="requerimientos" />
                            <input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
                            <input type="hidden" name="task" value="" />
							</form>
                        <!--</div>-->

                     	<!--<div class="gpti_derecha gpti_float_l" align="right">
                            <?php  //GPTIVistaGeneral::listas_derecha();?>
                            <br /><br /><br />
                    		<?php  //GPTIVistaGeneral::login();?>
                        </div>-->
						</td>
                    </tr>
               	</table>
            </div>
    	<?php
	}
	
	function  decision( $tabla )
	{
		global $Itemid;
		//GPTI_ASSETS_URL
		
		?>
            <div class="gpti_marco">
			
				<table cellpadding="4" cellspacing="0" border="0" width="100%" class="gpti_indice" summary="">
					<thead>
						<tr>
							<th><h1>TABLA : ÍNDICE</h1></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<div>
								<table cellpadding="4" cellspacing="0" border="0" width="100%" class="gpti_indice" summary="">
										<tr>
											<td colspan="3"><h2>AREAS DE DESARROLLO DE SOLUCIONES</h2></td>							
										</tr>
										<tr>
											<th width="10%" align="center">CÓDIGO</th>
											<th width="80%" align="center">DESCRIPCIÓN</th>
											<th width="10%" align="center">PUNTAJE</th>
										</tr>
										<?php foreach( $tabla['areas'] as $i => $row ) :  ?>
											<tr>
												<td><?php echo $row->ARE_CODIGO; ?></td>
												<td><?php echo $row->ARE_NOMBRE; ?></td>
												<td><?php echo $row->ARE_PUNTAJE; ?></td>
											</tr>
										<?php endforeach; ?>
								</table>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div>
								<table cellpadding="4" cellspacing="0" border="0" width="100%" class="gpti_indice" summary="">
									<tr>
										<td colspan="3"><h2>DIMENSIONES DE VALOR DE SOLUCIONES</h2></td>							
									</tr>
									<tr>
										<th width="10%" align="center">CÓDIGO</th>
										<th width="80%" align="center">DESCRIPCIÓN</th>
										<th width="10%" align="center">PUNTAJE</th>
									</tr>
									<?php foreach( $tabla['valores'] as $i => $row ) :  ?>
											<tr>
												<td><?php echo $row->VAS_CODIGO; ?></td>
												<td><?php echo $row->VAS_NOMBRE; ?></td>
												<td><?php echo $row->VAS_PUNTAJE; ?></td>
											</tr>
										<?php endforeach; ?>
								</table>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				<br />

				<table cellpadding="4" cellspacing="0" border="0" width="70%" class="gpti_indice" summary="">
					<thead>
						<tr>
							<th><h1>TABLA : DECISIÓN</h1></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<div>
								<table cellpadding="4" cellspacing="0" border="0" width="100%" class="gpti_indice" summary="">
										<tr>
											<th width="50%" align="center">CÓDIGO</th>
											<th width="25%" align="center">PUNTAJE DESDE</th>
											<th width="25%" align="center">PUNTAJE HASTA</th>
										</tr>
										<?php foreach( $tabla['tabla'] as $i => $row ) :  ?>
											<tr>
												<td><?php echo $row->RTD_CODIGO; ?></td>
												<td><?php echo $row->RTD_PUNTAJE_DESDE; ?></td>
												<td><?php echo ($row->RTD_PUNTAJE_HASTA ? $row->RTD_PUNTAJE_HASTA : ' y mas.' ); ?></td>
											</tr>
										<?php endforeach; ?>
								</table>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				
			</div>
		<?php
	}
}
?>