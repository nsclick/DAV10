 <?php
/**
 * @version		$Id: reconocimientos.php 2010-07-22 sgarcia $
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

class DoVistaReconocimientos
{	

	function display( &$rows, &$servicios, &$lists )
	{
		global $Itemid;
		
		?>
        <div class="componente reconocimientos" align="left">
            <h1>Reconocimientos</h1>
            <div class="box_descripcion">
              <p>Queremos reconocer a todos quienes cumplen una labor...</p>
            </div>
            <div class="box_descripcion_bottom"><img src="<?php echo JURI::base();?>images/pix_transparente.gif" alt="" width="710" height="18" /></div>
          <?php if( $lists['msj'] ) : ?>
          	<div class="formulario"><div class="msg"><?php echo $lists['msj'];?></div></div>
          <?php endif; ?>
       		<table width="710" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="160" align="left" valign="top" style="border-right:1px dotted #CCC;">
                <?php if( !is_array( $servicios ) || !count( $servicios ) ) : ?>
              		<div class="formulario"><div class="msg">No hay servicios por listar</div></div>
                <?php else : ?>
                	<ul class="servicios">
                    <?php foreach( $servicios as $servicio ) : ?>
                    	<li><a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=$Itemid&c=reconocimientos&task=".JRequest::getCmd('task')."&servicio=$servicio->servicio");?>" title="<?php echo $servicio->servicio;?>"<?php echo $servicio->servicio == $lists['servicio'] ? ' class="activo"':'';?>><?php echo $servicio->servicio;?></a></li>
                    <?php endforeach;?>
                    </ul>
                <?php endif; ?>
                </td>
                <td width="540" align="left" valign="top" style="padding-left:10px;">
                <?php if( !is_array( $rows ) || !count( $rows ) ) : ?>
              		<div class="formulario"><div class="msg">No hay reconocimientos en este periodo</div></div>
                <?php else : ?>
                  <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                    <?php foreach( $rows as $i => $row ) :
					$divprops 	= "";
					switch( $i % 3 ) :
						case 0:
							$align		= ' align="left"';
							$divprops	= $align.' class="itemizq"';
							break;
						case 1:
							$align		= ' align="center"';
							$divprops	= ' align="center" class="itemcen"';
							break;
						case 2:
							$align		= ' align="right"';
							$divprops	= ' align="left" class="itemder"';
							break;
					endswitch;
					
					echo $i && $i%3==0 ? '</tr><tr><td colspan="3"><img src="images/pix_transparente.gif" width="100%" height="20" alt="" /></td></tr><tr>':'';
					?>
                      <td width="33%"<?php echo $i % 3 != 0 ? ' style="border-left:1px dotted #CCC;"':'';?> valign="top">
                          <table cellpadding="0" cellspacing="0" border="0" width="100%" class="itemsgal">
                            <tr>
                              <td<?php echo $align;?>>
                                <img border="0"<?php echo $align==' align="center"'?'':$align;?> vspace="10" alt="" src="<?php echo $row->foto ?>" width="152" height="183" />
                              </td>
                            </tr>
                            <tr>
                              <td align="left" class="titulo" style="padding-right:20px;">
                                <a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=$Itemid&c=reconocimientos&task=ver&id=$row->id&tmpl=diploma");?>" title="Ver Diploma">
                                  <?php echo $row->nombre;?></a>
                                </td>
                            </tr>
                            <tr>
                              <td align="left" class="intro" style="padding-right:20px;"><?php echo $row->mensaje;?></td>
                            </tr>
                          </table>
                      </td>
                    <?php endforeach;?>
                    <?php
						$col	= count( $rows ) -1 % 3;
						if( !$col ) :
							echo '<td width="33%">&nbsp;</td><td width="33%">&nbsp;</td>';
						elseif( $col == 1 ) :
							echo '<td width="33%">&nbsp;</td>';
						endif;
					?>
                    </tr>
                  </table>
                <?php endif; ?>
                </td>
              </tr>
            </table>
       
       <?php /*if( !is_array( $rows ) || !count( $rows ) ) : ?>
          	<div class="formulario"><div class="msg"><?php echo $lists['msj'];?></div></div>
       <?php else : ?>
          <div class="itemsgal">
            <div class="spacer">
			<?php foreach ($rows as $i => $row) :
                $divprops 	= "";
                switch( $i % 4 ) :
                    case 0:
                        $align		= ' align="left"';
                        $divprops	= $align.' class="itemizq"';
                        break;
                    case 1:
                    case 2:
                        $align		= ' align="center"';
                        $divprops	= ' align="center" class="itemcen"';
                        break;
                    case 3:
                        $align		= ' align="right"';"
                        $divprops	= ' align="left" class="itemder"';
                        break;
                endswitch;
                
                echo $i && $i%4==0 ? '</div><div class="spacer">':'';
            ?>
              <div<?php echo $divprops;?>>
                  <table cellpadding="0" cellspacing="0" border="0" width="100%" class="itemsgal">
                    <tr>
                      <td<?php echo $align;?>><!--<img border="0"<?php echo $align==' align="center"'?'':$align;?> vspace="10" alt="" src="<?php echo $lists['tmpl'];?>/imagenes/reconocimiento1.jpg" />--><img border="0"<?php echo $align==' align="center"'?'':$align;?> vspace="10" alt="" src="<?php echo $row->foto;?>" /></td>
                    </tr>
                    <tr>
                      <td align="left" class="titulo" style="padding-right:20px;"><a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=$Itemid&c=reconocimientos&task=ver&id=$row->id&tmpl=diploma");?>" title="Ver Certificado"><?php echo $row->nombre;?></a></td>
                    </tr>
                    <tr>
                      <td align="left" class="intro" style="padding-right:20px;"><?php echo $row->mensaje;?></td>
                    </tr>
                  </table>
              </div>
            <?php endforeach;?>
            </div>
          </div>
       <?php endif;*/?>
        </div>
    	<?php
	}	

	function mantener( $rows, $lists )
	{
		global $Itemid;
		?>
        <script type="text/javascript">
			function mantenerEditar(id)
			{
				frm				= document.modReconocimiento;
				frm.id.value	= id;
				frm.task.value	= 'editar';
				frm.submit();
			}
			function mantenerEliminar(id)
			{
				if( confirm( "¿Está seguro que desea eliminar el reconocimiento?" ) )
				{
					frm				= document.modReconocimiento;
					frm.id.value	= id;
					frm.task.value	= 'eliminar';
					frm.submit();
				}
			}
		</script>
        <div class="componente reconocimientos" align="left">
          <form name="modReconocimiento" id="modReconocimiento" method="post" action="<?php echo JRoute::_("index.php?Itemid=$Itemid&c=reconocimientos");?>">
            <h1>Reconocimientos</h1>
            <div class="box_descripcion"><p>Aqu&iacute; usted puede, editar, eliminar y crear nuevos reconocimientos</p></div>
            <div class="box_descripcion_bottom"><img src="<?php echo JURI::base();?>images/pix_transparente.gif" alt="" width="710" height="18" /></div>
          <?php if( $lists['msj'] ) : ?>
          	<div class="formulario"><div class="msg"><?php echo $lists['msj'];?></div></div>
          <?php endif; ?>
            <div class="margen">
            	<div style="float:left; width:200px;" align="left"><input type="button" name="btncrear1" value="Crear un reconocimiento &raquo;" onclick="javascript:window.location='<?php echo JRoute::_("index.php?Itemid=$Itemid&c=reconocimientos&task=crear");?>';" class="button_2" /></div>
                <div style="float:left; width:510px;" align="right"><input type="button" name="btnvolver1" value="Volver a la lista de reconocimientos &raquo;" onclick="javascript:window.location='<?php echo JRoute::_("index.php?Itemid=$Itemid&c=reconocimientos");?>';" class="button_2" /></div>
            </div>
          <?php if( count( $rows ) ) : ?>
            <div class="margen">
				<table cellpadding="0" cellspacing="0" border="0" style="float:left; margin:10px 0px;" width="700">
                  <thead>
                    <tr>
                       <th width="12%">Fecha</th>
                       <th width="40%">Trabajador</th>
                       <th width="36%">Motivo</th>
                       <th width="12%">&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php foreach( $rows as $irow => $row ) : ?>
                    <tr>
                      <td><?php echo date("d-m-Y", strtotime($row->fecha));?></td>
                      <td><?php echo $row->nombre;?></td>
                      <td><?php echo substr( $row->mensaje, 0, 30).'...';?></td>
                      <td align="center">
                      	<a href="javascript:void(0);" onclick="javascript:mantenerEditar(<?php echo $row->id;?>); return false;" title="Editar Reconocimiento"><img src="<?php echo $lists['tmpl'];?>/imagenes/icon-32-edit.png" width="16" height="16" alt="Editar" border="0" /></a>
                        <a href="javascript:void(0);" onclick="javascript:mantenerEliminar(<?php echo $row->id;?>); return false;" title="Eliminar Reconocimiento"><img src="<?php echo $lists['tmpl'];?>/imagenes/icon-32-cancel.png" width="16" height="16" alt="Eliminar" border="0" /></a>
                        <a href="<?php echo JRoute::_("index.php?option=com_do&Itemid=$Itemid&c=reconocimientos&task=ver&id=$row->id&tmpl=diploma");?>" title="Ver Reconocimiento"><img src="<?php echo $lists['tmpl'];?>/imagenes/icon-32-forward.png" width="16" height="16" alt="Ver" border="0" /></a>
                      </td>
                    </tr>
                  <?php endforeach;?>
                  </tbody>
                </table>
            </div>
            <div class="margen">
            	<div style="float:left; width:200px;" align="left"><input type="button" name="btncrear2" value="Crear un reconocimiento &raquo;" onclick="javascript:window.location='<?php echo JRoute::_("index.php?Itemid=$Itemid&c=reconocimientos&task=crear");?>';" class="button_2" /></div>
                <div style="float:left; width:510px;" align="right"><input type="button" name="btnvolver2" value="Volver a la lista de reconocimientos &raquo;" onclick="javascript:window.location='<?php echo JRoute::_("index.php?Itemid=$Itemid&c=reconocimientos");?>';" class="button_2" /></div>
            </div>
          <?php endif;?>
            <input type="hidden" name="option" value="com_do" />
            <input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
            <input type="hidden" name="c" value="reconocimientos" />
            <input type="hidden" name="task" value="mantener" />
            <input type="hidden" name="id" value="0" />
          </form>
        </div>
        <?php
	}
	
	function crear( $reconocimiento, $row, $lists )
	{
		global $Itemid;
		?>
        <script type="text/javascript">
			function modReconocimiento_submit()
			{
				frm			= document.modReconocimiento;
				if( !frm.funcionario.selectedIndex )
				{
					alert("Debe seleccionar un trabajador");
					frm.funcionario.select();
					return false;
				}
				if( frm.comentarios.value == '' )
				{
					alert("Debe ingresar un saludo");
					frm.comentarios.focus();
					return false;
				}
				frm.submit();
			}
		</script>
        <div class="componente reconocimientos" align="left">
          <form name="modReconocimiento" id="modReconocimiento" method="post" action="<?php echo JRoute::_("index.php?Itemid=$Itemid&c=reconocimientos&tmpl=diploma");?>">
            <h1>Reconocimientos</h1>
            <div class="box_descripcion"><p>Favor complete los datos necesarios del reconocimiento</p></div>
            <div class="box_descripcion_bottom"><img src="<?php echo JURI::base();?>images/pix_transparente.gif" alt="" width="710" height="18" /></div>
          <?php if( $lists['msj'] ) : ?>
          	<div class="formulario"><div class="msg"><?php echo $lists['msj'];?></div></div>
          <?php endif; ?>
            <div class="margen">
            	<label for="funcionario">Trabajador:</label><br />
                <select name="funcionario" id="funcionario" class="inputbox">
                  <option value="">- Seleccionar -</option>
               <?php if( count( $row->subalternos ) ) : foreach( $row->subalternos as $subalterno ) : ?>
                  <option value="<?php echo $subalterno['RUT_FUNCIONARIO'];?>:<?php echo $subalterno['NOMBRE'];?>:<?php echo $subalterno['UNIDAD'];?>"<?php echo substr($subalterno['RUT_FUNCIONARIO'],0,-2) == $reconocimiento->rut ? ' selected="selected"':'';?>><?php echo $subalterno['NOMBRE'];?></option>
               <?php endforeach; endif; ?>
                </select>
            </div>
            <div class="margen">
            	<label for="comentarios">Motivo del reconocimiento:</label><br />
                <input type="text" name="comentarios" id="comentarios" class="inputbox" style="width:300px;" value="<?php echo $reconocimiento->mensaje;?>" />
            	<!--<textarea name="comentarios" id="comentarios2" class="inputbox_area" rows="6" cols="" style="width:99%;"></textarea>-->
            </div>
            <div class="margen">
            	<div style="float:left; width:200px;" align="left"><input type="button" name="btnsaludar" value="Previsualizar reconocimiento &raquo;" onclick="javascript:modReconocimiento_submit();" class="button_2" /></div>
                <div style="float:left; width:510px;" align="right"><input type="button" name="btnvolver" value="Volver al mantenedor de reconocimientos &raquo;" onclick="javascript:window.location='<?php echo JRoute::_("index.php?Itemid=$Itemid&c=reconocimientos&task=mantener");?>';" class="button_2" /></div>
            </div>
            <input type="hidden" name="option" value="com_do" />
            <input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
            <input type="hidden" name="c" value="reconocimientos" />
            <input type="hidden" name="id" value="<?php echo $reconocimiento->id;?>" />
            <input type="hidden" name="task" value="preview" />
            <input type="hidden" name="tmpl" value="diploma" />
          </form>
        </div>
        <?php
	}
	
	function preview( &$row, &$lists )
	{
		global $Itemid;
		?>
        <script type="text/javascript">
			function reconocimientoVolver()
			{
				var frm			= document.reconocimientoPreview;
				frm.task.value	= 'editar';
				frm.submit();
			}
			
			function reconocimientoConfirmar()
			{
				var frm			= document.reconocimientoPreview;
				frm.task.value	= 'reconocer';
				frm.submit();
			}
		</script>
        <div class="componente reconocimientos" align="center">
          <form name="reconocimientoPreview" id="reconocimientoPreview" method="post" action="<?php echo JRoute::_("index.php?Itemid=$Itemid&c=reconocimientos");?>">
            <div class="margen" style="padding-bottom:20px; width:996px;" align="left">
                <div class="diploma_nombre"><?php echo $row->nombre;?></div>
                <div class="diploma_motivo"><?php echo $row->mensaje;?></div>
                <div class="diploma_jefe"><?php echo $row->jefenombre;?></div>
            	<table width="996" cellpadding="0" cellspacing="0" border="0" align="center" class="diploma">
                  <tr>
                    <td colspan="3"><img src="<?php echo $lists['tmpl'];?>/imagenes/diploma_top.jpg" alt="" border="0" /></td>
                  </tr>
                  <tr>
                    <td width="762"><img src="<?php echo $lists['tmpl'];?>/imagenes/diploma_left.jpg" alt="" border="0" /></td>
                    <td width="152" valign="middle" align="center" bgcolor="#FFFFFF"><img border="0" align="middle" alt="<?php echo $row->nombre;?>" src="foto.php?rut=<?php echo $row->rut;?>&amp;ancho=152&amp;alto=183&amp;esquinas=1" width="152" height="183" /></td>
                    <td width="82"><img src="<?php echo $lists['tmpl'];?>/imagenes/diploma_right.jpg" alt="" border="0" /></td>
                  </tr>
                  <tr>
                    <td colspan="3"><img src="<?php echo $lists['tmpl'];?>/imagenes/diploma_bottom.jpg" alt="" border="0" /></td>
                  </tr>
                  <tr>
                    <td colspan="3">
                        <div class="margen">
                            <div style="float:left; width:492px;" align="left"><input type="button" name="btneditar" value="&laquo; Editar Reconocimiento" onclick="javascript:reconocimientoVolver();" class="button_2" /></div>
                            <div style="float:left; width:492px;" align="right"><input type="button" name="btnconfirmar" value="Confirmar reconocimiento &raquo;" onclick="javascript:reconocimientoConfirmar();" class="button_2" /></div>
                        </div>
                    </td>
                  </tr>
                </table>
            </div>
            <input type="hidden" name="option" value="com_do" />
            <input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
            <input type="hidden" name="c" value="reconocimientos" />
            <input type="hidden" name="task" value="preview" />
            <input type="hidden" name="id" value="<?php echo $row->id;?>" />
            <input type="hidden" name="funcionario" value="<?php echo $row->rut;?>" />
            <input type="hidden" name="nombre" value="<?php echo $row->nombre;?>" />
            <input type="hidden" name="unidad" value="<?php echo $row->unidad;?>" />
            <input type="hidden" name="comentarios" value="<?php echo $row->mensaje;?>" />
            <input type="hidden" name="tmpl" value="" />
          </form>
        </div>
        <?php
	}
	
	function ver( &$row, &$lists )
	{
		global $Itemid;
		?>
        <div class="componente reconocimientos" align="center">
            <div class="margen" style="padding-bottom:20px; width:996px;" align="left">
                <div class="diploma_nombre<?php echo JRequest::getVar( 'tmpl', '', "REQUEST" ) == "componente"?'_impresion':'';?>"><?php echo $row->nombre;?></div>
                <div class="diploma_motivo<?php echo JRequest::getVar( 'tmpl', '', "REQUEST" ) == "componente"?'_impresion':'';?>"><?php echo $row->mensaje;?></div>
                <div class="diploma_jefe<?php echo JRequest::getVar( 'tmpl', '', "REQUEST" ) == "componente"?'_impresion':'';?>"><?php echo $row->jefenombre;?></div>
            	<table width="996" cellpadding="0" cellspacing="0" border="0" align="center" class="diploma">
                  <tr>
                    <td colspan="3"><img src="<?php echo $lists['tmpl'];?>/imagenes/diploma_top.jpg" alt="" border="0" /></td>
                  </tr>
                  <tr>
                    <td width="762">
                      <img src="<?php echo $lists['tmpl'];?>/imagenes/diploma_left.jpg" alt="" border="0" />
                    </td>
                    <td width="152" valign="middle" align="center" bgcolor="#FFFFFF">
                      <img border="0" align="middle" alt="<?php echo $row->nombre;?>" src="foto.php?rut=<?php echo $row->rutdv;?>&amp;ancho=152&amp;alto=163&amp;esquinas=1" width="152" height="183" />
                    </td>
                    <td width="82">
                      <img src="<?php echo $lists['tmpl'];?>/imagenes/diploma_right.jpg" alt="" border="0" />
                    </td>
                  </tr>
                  <tr>
                    <td colspan="3"><img src="<?php echo $lists['tmpl'];?>/imagenes/diploma_bottom.jpg" alt="" border="0" /></td>
                  </tr>
                  <tr>
                    <td colspan="3">
                        <div class="margen">
                        <?php if( $lists['puedeImprimir'] ) : ?>
                            <div style="float:left; width:492px;" align="left"><input type="button" name="btnimprimir" value="Imprimir" onclick="<?php echo $lists['imprimir'];?>" class="button_2" /></div>
                        <?php endif;?>
                        <?php if(JRequest::getVar( 'tmpl', '', "REQUEST" ) != "componente") : ?>
                            <div style="float:left; width:<?php echo $lists['puedeImprimir'] ? 492:250;?>px;" align="right"><input type="button" name="btnvolver" value="Volver a la lista de reconocimientos &raquo;" onclick="javascript:window.location='<?php echo JRoute::_("index.php?Itemid=$Itemid&c=reconocimientos");?>';" class="button_2" /></div>
                        <?php endif;?>
                        </div>
                    </td>
                  </tr>
                </table>
            </div>
        </div>
        <?php
	}
	
}

?>