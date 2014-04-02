<?php
/**
 * @version		$Id: cotizaciones.php 2010-06-11 sgarcia $
 * @package		Joomla
 * @subpackage	DO
 * @autor		Diseño Objetivo wwww.do.cl
 * @copyright	Copyright (C) 2006 - 2010 Diseño Objetivo. Todos los derechos reservados.
 * @license		LICENCIA_DO.php
 */

	/**************************************/
	/*                                    */
	/*          Diseño Objetivo           */
	/*     			www.do.cl    	  	  */
	/*   		 contacto@do.cl  		  */
	/*      Fono: (56-02) 228 13 91       */
	/*                                    */
	/**************************************/
	
// sin acceso directo
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * @package		Joomla
 * @subpackage	DO
 */
class DoVistaCotizaciones
{

	function display( &$rows, &$lists, &$pageNav )
	{
		JToolBarHelper::title( JText::_( 'CashBox - Cotizaciones' ), 'do.png' );
		JToolBarHelper::editListX();
		
		$user =& JFactory::getUser();
		JHTML::_('behavior.tooltip'); 
		?>
		<form action="index.php?option=com_do" method="post" name="adminForm">
		<table>
		<tr>
			<td align="left" width="100%">
				<?php echo JText::_( 'Filter' ); ?>:
				<input type="text" name="filtro_palabra" id="filtro_palabra" value="<?php echo $lists['palabra'];?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById('filtro_palabra').value='';this.form.getElementById('filtro_desde').value='';this.form.getElementById('filtro_hasta').value='';this.form.getElementById('filter_ejecutivo').value=0;this.form.getElementById('filter_state').value=0;this.form.submit();"><?php echo JText::_( 'Filter Reset' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php
				echo "&nbsp;".$lists['cliente'];
				echo "&nbsp;".$lists['desde'];
				echo "&nbsp;".$lists['hasta'];
				echo "&nbsp;".$lists['ejecutivo'];
				echo "&nbsp;".$lists['estado'];
				?>
			</td>
		</tr>
		</table>

			<table class="adminlist">
			<thead>
				<tr>
					<th width="20">
						<?php echo JText::_( 'Num' ); ?>
					</th>
					<th width="20">
						<input type="checkbox" name="toggle" value=""  onclick="checkAll(<?php echo count( $rows ); ?>);" />
					</th>
					<th width="6%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort',   'Nro.', 'c.id', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th nowrap="nowrap" class="title">
						<?php echo JHTML::_('grid.sort',  'Cliente', 'dc.nombre', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="15%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort',   'Estado', 'c.estado', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="15%">
						<?php echo JHTML::_('grid.sort',   'Fecha', 'c.fecha', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="15%">
						<?php echo JHTML::_('grid.sort',   'Fecha Envío', 'c.fechaenvio', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="15%"><?php echo JText::_("Total");?></th>
					<th width="8%">
						<?php echo JHTML::_('grid.sort',   'PDF', '', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="8%">
						<?php echo JHTML::_('grid.sort',   'HTML', '', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="13">
						<?php echo $pageNav->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php
			$k = 0;
			for ($i=0, $n=count( $rows ); $i < $n; $i++) {
				$row 		= &$rows[$i];
				$link		= JRoute::_( 'index.php?option=com_do&c=cotizaciones&task=edit&cid[]='. $row->id );
				$checked	= JHTML::_('grid.checkedout',   	$row, $i );
				?>
                
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
						<?php echo $pageNav->getRowOffset($i); ?>
					</td>
					<td align="center">
						<?php echo $checked; ?>
					</td>
					<td align="center">
						<?php echo $row->id;?>
					</td>
					<td>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit' );?>::<?php echo $row->cliente; ?>">
						<?php
						if ( JTable::isCheckedOut($user->get ('id'), $row->checked_out ) ) {
							echo $row->cliente;
							echo $row->editor;
							
						} else {
							?>

							<a href="<?php echo $link; ?>">
								<?php echo $row->cliente; ?></a>
							<?php
						}
						?>
						</span>
					</td>
					<td align="center">
						<?php echo $row->estado_alias;?>
					</td>
					<td align="center">
						<?php echo $row->fecha;?>
					</td>
					<td align="center">
						<?php echo $row->fechaenvio;?>
					</td>
                    <td align="center">
						<?php echo $row->totalbruto_html;?>
					</td>
					<td align="center">
						<?php echo $row->pdf;?>
					</td>
					<td align="center">
						<?php echo $row->html;?>
					</td>
				</tr>
				<?php
				++$k;
			}
			?>
            
			</tbody>
			</table>

		<input type="hidden" name="c" value="cotizaciones" />
		<input type="hidden" name="option" value="com_do" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
     	<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
        
		<?php
	}

	function edit( &$row, &$lists )
	{
		$task = JRequest::getVar( 'task', '', 'method', 'string');

		JToolBarHelper::title( $task == 'add' ? 'CashBox - Cotizaciones' . ': <small><small>[ '. 'Nuevo' .' ]</small></small>' : 'CashBox - Cotizaciones' . ': <small><small>[ '. JText::_( 'Edit' ) .' ]</small></small>', 'do.png' );
		JToolBarHelper::save( 'save' );
		JToolBarHelper::apply('apply');
		JToolBarHelper::customX('enviar', 'send.png', '', 'Enviar', false);
		JToolBarHelper::cancel( 'cancel' );
		
		$user		=& JFactory::getUser();
		JRequest::setVar( 'hidemainmenu', 1 );
		?>
        <script type="text/javascript">
			var DOM = document.getElementById;
			var IE4 = document.all;
			var NN4 = document.layers;
					
			<?php echo $lists['script'];?>
					
			var productos = new Array();
			<?php
			if( count( $row->productos ) )
			{
				foreach( $row->productos as $p => $producto )
				{
			?>
			productos[<?php echo $p;?>] = <?php echo $producto->id;?>;
			<?php
				}
			}
			?>
		
			function numeroApesos(num,html){
				strnum = String(num);
				largo=strnum.length;
				numeros = new Array();
					for(k=0; k < largo; ++k){
						numeros[k] = strnum.charAt(k);
					}
				fracc=largo/3;
				numpts = ( Math.floor(fracc) == fracc ) ? --fracc : Math.floor(fracc);
				peso = (!html)?"$":"&#36;";
				indice = ( largo%3 == 0 ) ? 2 : largo%3-1;
				npts=0;
				for(j=0; j < largo; ++j){
					peso+=numeros[j];
					if((npts<numpts)&&(j==indice)){
						peso+=".";
						indice+=3;
						++npts;
					}
				}
				return(peso);
			}
		
			function valores()
			{
				var objform		= document.adminForm;
				var neto		= 0;
				var iva			= 0;
				var bruto		= 0;
				
				for( p=0; p < productos.length; ++p )
				{
					id	= productos[p];
					
					//prod_cantidad
					//prod_precio
					//prod_total
					//prod_total_html
					objcantidad = null;
					if(DOM){ var objcantidad = document.getElementById('cantidades'+id);
					}else if(IE4){ var objcantidad = document.all['cantidades'+id];
					}else if(NN4){ var objcantidad = document.layers['cantidades'+id]; }
					
					objprecio = null;
					if(DOM){ var objprecio = document.getElementById('precios'+id);
					}else if(IE4){ var objprecio = document.all['precios'+id];
					}else if(NN4){ var objprecio = document.layers['precios'+id]; }
					
					objtotalhtml = null;
					if(DOM){ var objtotalhtml = document.getElementById('totales'+id);
					}else if(IE4){ var objtotalhtml = document.all['totales'+id];
					}else if(NN4){ var objtotalhtml = document.layers['totales'+id]; }
					
					if( objcantidad != null && objprecio != null && objtotalhtml != null )
					{
						// parse
						objcantidad.value		= parseInt( objcantidad.value );
						objprecio.value			= parseInt( objprecio.value );
						
						total					= Math.round( objcantidad.value * objprecio.value );
						//objtotal.value			= total;
						objtotalhtml.innerHTML	= numeroApesos( total, true );
						
						neto					+= parseInt( total );
					}
				}
				
				//subtotalhtml
				//subtotal
				subtotal						= neto
				objform.subtotal.value			= subtotal;
				objsubtotalhtml = null;
				if(DOM){ var objsubtotalhtml = document.getElementById('subtotalhtml');
				}else if(IE4){ var objsubtotalhtml = document.all['subtotalhtml'];
				}else if(NN4){ var objsubtotalhtml = document.layers['subtotalhtml']; }
				objsubtotalhtml.innerHTML	= numeroApesos( subtotal, true );
				
				neto						-= parseInt( objform.descuento.value );
				
				objform.neto.value			= neto;
				objnetohtml = null;
				if(DOM){ var objnetohtml = document.getElementById('netohtml');
				}else if(IE4){ var objnetohtml = document.all['netohtml'];
				}else if(NN4){ var objnetohtml = document.layers['netohtml']; }
				objnetohtml.innerHTML	= numeroApesos( neto, true );
				
				objform.iva.value			= Math.round( neto * 0.19 );
				objivahtml = null;
				if(DOM){ var objivahtml = document.getElementById('ivahtml');
				}else if(IE4){ var objivahtml = document.all['ivahtml'];
				}else if(NN4){ var objivahtml = document.layers['ivahtml']; }
				objivahtml.innerHTML	= numeroApesos( objform.iva.value, true );
				
				objform.bruto.value			= parseInt(neto) + parseInt(objform.iva.value);
				objbrutohtml = null;
				if(DOM){ var objbrutohtml = document.getElementById('brutohtml');
				}else if(IE4){ var objbrutohtml = document.all['brutohtml'];
				}else if(NN4){ var objbrutohtml = document.layers['brutohtml']; }
				objbrutohtml.innerHTML	= numeroApesos( objform.bruto.value, true );
			}
					
			function frmEliminar( id )
			{
				//tabla_productos
				tabla = null;
				if(DOM){ var tabla = document.getElementById('tabla_productos');
				}else if(IE4){ var tabla = document.all['tabla_productos'];
				}else if(NN4){ var tabla = document.layers['tabla_productos']; }
				
				//pro_tr_1
				fila = null;
				if(DOM){ var fila = document.getElementById('pro_tr_'+id);
				}else if(IE4){ var fila = document.all['pro_tr_'+id];
				}else if(NN4){ var fila = document.layers['pro_tr_'+id]; }
				
				if( tabla != null && fila != null )
				{
					tabla.tBodies[0].removeChild( fila );
					valores();
				}
			}
					
			function frmAgregar()
			{
				frm				= document.adminForm;
				
				if( !frm.pro_producto.selectedIndex )
				{
					alert("Favor debe seleccionar un producto");
					frm.pro_producto.focus();
					return false;
				}
				frm.task.value	= 'editar_add';
				frm.submit();
			}
					
			function frmCancelar()
			{
				frm				= document.adminForm;
				frm.id.value	= 0;
				frm.task.value	= 'display';
				frm.submit();
			}
		</script>
        <form action="index.php" method="post" name="adminForm">
		<div class="col100">
   			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Cliente' ); ?></legend>
				<table class="admintable">
				<tbody>
					<tr>
						<td class="key">Cliente :</td>
						<td><?php echo $row->cliente;?></td>
					</tr>
                 <?php if( $row->rut ): ?>
					<tr>
						<td class="key">Rut :</td>
						<td><?php echo $row->rut;?></td>
					</tr>
                 <?php endif; ?>
					<tr>
						<td class="key">Email :</td>
						<td><?php echo $row->email;?></td>
					</tr>
				</tbody>
				</table>
			</fieldset>
   			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Details' ); ?></legend>
				<table class="admintable">
				<tbody>
					<tr>
						<td class="key">Nro. :</td>
						<td><?php echo $row->id;?></td>
					</tr>
					<tr>
						<td class="key">Estado :</td>
						<td><?php echo /*$lists['estado']*/$row->estado_alias;?></td>
					</tr>
                    <tr>
						<td class="key">Fecha :</td>
						<td><?php echo $row->fecha; ?></td>
					</tr>
					<tr>
						<td class="key" valign="top"><label for="descripcion">Descripción :</label></td>
						<td><textarea name="descripcion" id="descripcion" class="inputbox" rows="5" cols="70"><?php echo $row->descripcion;?></textarea></td>
					</tr>
					<?php /*<tr>
						<td class="key"><label for="validez">Validez :</label></td>
						<td><input class="inputbox" type="text" name="validez" id="validez" size="100" value="<?php echo $row->validez;?>" /></td>
					</tr>
					<tr>
						<td class="key"><label for="formapago">Forma de Pago :</label></td>
						<td><input class="inputbox" type="text" name="formapago" id="formapago" size="100" value="<?php echo $row->formapago;?>" /></td>
					</tr>*/?>
				</tbody>
				</table>
			</fieldset>
		<?php
        if( count( $row->productos ) ) :
        ?>
   			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Productos' ); ?></legend>
				<table class="admintable">
				<tbody>
					<tr>
						<td colspan="2">
                        	<table class="admintable" id="tabla_productos">
                            <thead>
                            	<tr>
                                	<!--<th align="center">Imagen</th>-->
                                    <th align="center">Código</th>
                                    <th align="center">Detalle</th>
                                    <th align="center">Cantidad</th>
                                    <th align="center">Precio Unitario</th>
                                    <th align="center">Valor Total</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
							<?php
                            foreach( $row->productos as $p => $producto ) :
                                ?>
                            	<tr id="pro_tr_<?php echo $producto->id;?>">
                                	<!--<td valign="middle" align="center"><?php echo $producto->img_miniatura;?></td>-->
                                    <td valign="middle" align="center"><input type="hidden" name="pid[]" value="<?php echo $producto->id;?>" /><?php echo $producto->codigo;?></td>
                                    <td valign="middle" align="center"><input type="text" name="descripciones<?php echo $producto->id;?>" id="descripciones<?php echo $producto->id;?>" class="inputbox" size="50" value="<?php echo $producto->descripcion;?>" /></td>
                                    <td valign="middle" align="center"><input class="inputbox" type="text" name="cantidades<?php echo $producto->id;?>" id="cantidades<?php echo $producto->id;?>" size="10" value="<?php echo $producto->cantidad;?>" style="text-align:center;" onblur="javascript:valores();" /></td>
                                    <td valign="middle" align="center">$<input class="inputbox" type="text" name="precios<?php echo $producto->id;?>" id="precios<?php echo $producto->id;?>" size="20" value="<?php echo $producto->precio;?>" style="text-align:center;" onblur="javascript:valores();" /></td>
                                    <td valign="middle" align="center" id="totales<?php echo $producto->id;?>"><?php echo $producto->total_html;?></td>
                                    <td valign="middle" align="center"><a href="javascript:void(0);" onclick="javascript:frmEliminar(<?php echo $producto->id;?>);" title="Eliminar Producto"><img src="images/publish_x.png" alt="" border="0" /></a></td>
                                </tr>
								<?php
                            endforeach;
                            ?>
                            </tbody>
                            </table>
                        </td>
					</tr>
					<tr>
						<td class="key">Sub Total Neto :</td>
						<td><input type="hidden" name="subtotal" id="subtotal" value="<?php echo $row->subtotalneto;?>" /><span id="subtotalhtml"><?php echo $row->subtotalneto_html;?></span></td>
					</tr>
					<tr>
						<td class="key">Descuento Neto :</td>
						<td><input type="text" name="descuento" id="descuento" value="<?php echo $row->descuento;?>" style="text-align:center;" class="inputbox" size="12" /></span></td>
					</tr>
					<tr>
						<td class="key"><hr /></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td class="key">Total Neto :</td>
						<td><input type="hidden" name="neto" id="neto" value="<?php echo $row->totalneto;?>" /><span id="netohtml"><?php echo $row->totalneto_html;?></span></td>
					</tr>
					<tr>
						<td class="key">Total IVA :</td>
						<td><input type="hidden" name="iva" id="iva" value="<?php echo $row->totaliva;?>" /><span id="ivahtml"><?php echo $row->totaliva_html;?></span></td>
					</tr>
					<tr>
						<td class="key">Total Bruto :</td>
						<td><input type="hidden" name="bruto" id="bruto" value="<?php echo $row->totalbruto;?>" /><span id="brutohtml"><?php echo $row->totalbruto_html;?></span></td>
					</tr>
					<tr>
                    	<td colspan="2"><input type="button" name="calcular" value="Calcular" onclick="javascript:valores();" /></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                        <fieldset>
                            <legend>Agregar Producto</legend>
                            <label for="pro_catid">Categoría:</label> <?php echo $lists['pro_catid'];?><br />
                            <label for="pro_producto">Producto:</label> <?php echo $lists['pro_producto'];?><br />
                            <input type="button" name="btnagregar" value="Agregar" onclick="javascript:frmAgregar();" class="boton" />
                        </fieldset>
                        </td>
                    </tr>
				</tbody>
				</table>
			</fieldset>
		<?php
		endif;
        ?>
		</div>
		<div class="clr"></div>
        <input type="hidden" name="validez" value="15 días *" />
        <input type="hidden" name="formapago" value="Contado" />
		<input type="hidden" name="c" value="cotizaciones" />
		<input type="hidden" name="option" value="com_do" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="edit" />
		
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
        <?php 
	}

	function ver( &$row, &$lists )
	{
		$task = JRequest::getVar( 'task', '', 'method', 'string');

		JToolBarHelper::title( $task == 'add' ? 'CashBox - Cotizaciones' . ': <small><small>[ '. 'Nuevo' .' ]</small></small>' : 'CashBox - Cotizaciones' . ': <small><small>[ '. JText::_( 'Edit' ) .' ]</small></small>', 'do.png' );
		//JToolBarHelper::customX('copiar', 'copy.png', '', 'Copiar', false);
		JToolBarHelper::cancel( 'cancel' );
		
		$user		=& JFactory::getUser();
		JRequest::setVar( 'hidemainmenu', 1 );
		?>
        <script type="text/javascript">
			var DOM = document.getElementById;
			var IE4 = document.all;
			var NN4 = document.layers;
					
			function frmCopiar()
			{
				frm				= document.adminForm;
				
				if( !frm.cliente.selectedIndex )
				{
					alert("Favor debe seleccionar un cliente");
					frm.cliente.focus();
					return false;
				}
				frm.task.value	= 'copiar';
				frm.submit();
			}
		</script>
        <form action="index.php" method="post" name="adminForm">
		<div class="col100">
   			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Cliente' ); ?></legend>
				<table class="admintable">
				<tbody>
					<tr>
						<td class="key">Cliente :</td>
						<td><?php echo $row->cliente;?></td>
					</tr>
                 <?php if( $row->rut ): ?>
					<tr>
						<td class="key">Rut :</td>
						<td><?php echo $row->rut;?></td>
					</tr>
                 <?php endif; ?>
					<tr>
						<td class="key">Email :</td>
						<td><?php echo $row->email;?></td>
					</tr>
				</tbody>
				</table>
			</fieldset>
   			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Details' ); ?></legend>
				<table class="admintable">
				<tbody>
					<tr>
						<td class="key">Nro. :</td>
						<td><?php echo $row->id;?></td>
					</tr>
					<tr>
						<td class="key">Estado :</td>
						<td><?php echo $row->estado_alias;?></td>
					</tr>
                    <tr>
						<td class="key">Fecha :</td>
						<td><?php echo $row->fecha; ?></td>
					</tr>
					<tr>
						<td class="key" valign="top">Descripción :</td>
						<td><?php echo $row->descripcion;?></td>
					</tr>
					<tr>
						<td class="key">Validez :</td>
						<td><?php echo $row->validez;?></td>
					</tr>
					<tr>
						<td class="key">Forma de Pago :</td>
						<td><?php echo $row->formapago;?></td>
					</tr>
				</tbody>
				</table>
			</fieldset>
		<?php
        if( count( $row->productos ) ) :
        ?>
   			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Productos' ); ?></legend>
				<table class="admintable">
				<tbody>
					<tr>
						<td colspan="2">
                        	<table class="admintable" id="tabla_productos">
                            <thead>
                            	<tr>
                                	<th align="center">Imagen</th>
                                    <th align="center">Código</th>
                                    <th align="center">Descripción</th>
                                    <th align="center">Cantidad</th>
                                    <th align="center">Precio Unitario</th>
                                    <th align="center">Valor Total</th>
                                </tr>
                            </thead>
                            <tbody>
							<?php
                            foreach( $row->productos as $p => $producto ) :
                                ?>
                            	<tr id="pro_tr_<?php echo $producto->id;?>">
                                	<td valign="middle" align="center"><?php echo $producto->img_miniatura;?></td>
                                    <td valign="middle" align="center"><input type="hidden" name="pid[]" value="<?php echo $producto->id;?>" /><?php echo $producto->codigo;?></td>
                                    <td valign="middle" align="center"><?php echo $producto->descripcion;?></td>
                                    <td valign="middle" align="center"><?php echo $producto->cantidad;?></td>
                                    <td valign="middle" align="center"><?php echo $producto->precio_html;?></td>
                                    <td valign="middle" align="center" id="totales<?php echo $producto->id;?>"><?php echo $producto->total_html;?></td>
                                </tr>
								<?php
                            endforeach;
                            ?>
                            </tbody>
                            </table>
                        </td>
					</tr>
					<tr>
						<td class="key">Sub Total Neto :</td>
						<td><?php echo $row->subtotalneto_html;?></td>
					</tr>
					<tr>
						<td class="key">Descuento Neto :</td>
						<td><?php echo $row->descuento_html;?></td>
					</tr>
					<tr>
						<td colspan="2"><hr /></td>
					</tr>
					<tr>
						<td class="key">Total Neto :</td>
						<td><?php echo $row->totalneto_html;?></td>
					</tr>
					<tr>
						<td class="key">Total IVA :</td>
						<td><?php echo $row->totaliva_html;?></td>
					</tr>
					<tr>
						<td class="key">Total Bruto :</td>
						<td><?php echo $row->totalbruto_html;?></td>
					</tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                        <fieldset>
                            <legend>Copiar Cotización</legend>
                            <label for="cliente">Cliente:</label> <?php echo $lists['cliente'];?><br />
                            <input type="button" name="btncopiar" value="Copiar" onclick="javascript:frmCopiar();" class="boton" />
                        </fieldset>
                        </td>
                    </tr>
				</tbody>
				</table>
			</fieldset>
		<?php
		endif;
        ?>
		</div>
		<div class="clr"></div>

		<input type="hidden" name="c" value="cotizaciones" />
		<input type="hidden" name="option" value="com_do" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="editar" />
		
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
        <?php 
	}
	
}

