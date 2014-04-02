 <?php
/**
 * @version		$Id: cupones.php 2010-07-22 sgarcia $
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

class DoVistaCupones
{	

	function display( &$lists )
	{
		global $Itemid;
		
		?>
        <script type="text/javascript">
			jQuery(document).ready(function()
			{
				jQuery.post('index.php', { option: "com_do", c: "cupones", task: "lista", tmpl: "component", Itemid: "<?php echo $Itemid;?>" }, function(data)
				{
					jQuery('#com_do_cupones').html(data);
				});
			});
		</script>
        <div class="componente reconocimientos" align="left">
            <h1>Cupones de Cumpleaños</h1>
            <div class="box_descripcion">
              <p>Aquí usted podrá Reactivar/Desactivar los cupones de cumpleños del personal</p>
            </div>
            <div class="box_descripcion_bottom"><img src="<?php echo JURI::base();?>images/pix_transparente.gif" alt="" width="710" height="18" /></div>
          <?php if( $lists['msj'] ) : ?>
          	<div class="formulario"><div class="msg"><?php echo $lists['msj'];?></div></div>
          <?php endif; ?>
       		
            <div class="formulario margen" id="com_do_cupones"></div>
        </div>
    	<?php
	}	

	function lista( $rows, $lists, $pageNav )
	{
		global $Itemid;
		?>
        <script type="text/javascript">
			function mantenerCupon(cid,estado)
			{
				var t = estado == 1 ? 'reactivar' : 'desactivar';
				jQuery.post('index.php', { option: "com_do", c: "cupones", task: t, id: cid, tmpl: "component", Itemid: "<?php echo $Itemid;?>" }, function(data)
				{
					jQuery('#com_do_cupones').html(data);
				});
			}
		</script>
        <form name="doCupones" action="<?php echo JRoute::_("index.php?option=com_do&Itemid=$Itemid");?>" method="post">
          <?php if( count( $rows ) ) : ?>
            <div class="margen">
				<table cellpadding="0" cellspacing="0" border="0" style="float:left; margin:10px 0px;" width="700">
                  <thead>
                    <tr>
                       <th width="12%">Usuario</th>
                       <th width="40%">Nombre</th>
                       <th width="20%">Fecha Nacimiento</th>
                       <th width="20%">Fecha Impresión</th>
                       <th width="8%">&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php foreach( $rows as $irow => $row ) : ?>
                    <tr>
                      <td><?php echo $row->usuario;?></td>
                      <td><?php echo $row->nombre;?></td>
                      <td><?php echo date("d-m-Y", strtotime($row->nacimiento));?></td>
                     <td><?php echo $row->impresion != '0000-00-00 00:00:00' ? date("d-m-Y H:i", strtotime($row->impresion)) : 'Reactivado';?></td>
                      <td align="center">
                      <?php if( $row->impresion != '0000-00-00 00:00:00' ) : ?>
                        <a href="javascript:void(0);" onclick="javascript:mantenerCupon(<?php echo $row->id;?>,1); return false;" title="Reactivar Cupón"><img src="images/apply_f2.png" width="16" height="16" alt="Reactivar Cupón" border="0" /></a>
                      <?php else : ?>
                      	<a href="javascript:void(0);" onclick="javascript:mantenerCupon(<?php echo $row->id;?>,0); return false;" title="Desactivar Cupón"><img src="images/cancel_f2.png" width="16" height="16" alt="Desactivar Cupón" border="0" /></a>
                      <?php endif;?>
                      </td>
                    </tr>
                  <?php endforeach;?>
                  </tbody>
                </table>
            </div>
            <div class="margen">
            	<?php echo $pageNav->getPaginadorComponente(); ?>
            </div>
          <?php else : ?>
          	<div class="msj">
            	No hay registros disponibles
            </div>
          <?php endif;?>
          <input type="hidden" name="option" value="com_do" />
          <input type="hidden" name="c" value="cupones" />
          <input type="hidden" name="task" value="" />
          <input type="hidden" name="id" value="" />
          <input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
        </form>
        <?php
	}
		
}

?>