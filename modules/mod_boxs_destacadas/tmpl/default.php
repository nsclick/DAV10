<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
		$app					=& JFactory::getApplication();
		$template				= JURI::base() . 'templates/' . $app->getTemplate();
?>
<div class="box"<?php echo $params->get('margen') ? ' style="margin-left:2px;"':' style="width:314px;"';?>>
  <table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
      <td width="8"><img src="<?php echo $template;?>/imagenes/boxsdestacadas_izquierda.jpg" alt="" border="0" /></td>
      <td width="100%" align="left">
      	<div style="float:left; width:30px;"><?php echo $datos->icono;?></div>
        <div style="float:left; width:260px;">
        	<div class="tit"<?php if($params->get('autoconsulta')):?> style="color:#F00;"<?php endif;?>><?php echo $datos->titulo;?></div>
          <?php if( $datos->descripcion ) : ?>
        	<div class="txt"><?php echo $datos->descripcion;?></div>
          <?php endif;?>
          <?php if( !$params->get('autoconsulta') || ( $params->get('autoconsulta') && IPprivada() ) ) : ?>
        	<div class="link" align="right"><a href="<?php echo $datos->link;?>" title="Ver m&aacute;s" class="boxs_link"<?php echo $datos->browserNav ? ' target="_blank"':'';?>><?php echo $params->get('vermas') ? '<img src="'.$template.'/imagenes/boxsdestacadas_vermas.jpg" alt="Ver m&aacute;s" border="0" title="Ver m&aacute;s" />':'Ver m&aacute;s &raquo;';?></a></div>
          <?php endif;?>
        </div>
      </td>
      <td width="8"><img src="<?php echo $template;?>/imagenes/boxsdestacadas_derecha.jpg" alt="" border="0" /></td>
    </tr>
  </table>
</div>