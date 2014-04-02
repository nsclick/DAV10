<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
		$app					=& JFactory::getApplication();
		$template				= JURI::base() . 'templates/' . $app->getTemplate();
?>
<div class="mod_formulario" align="left">
  <div style="float:left; width:204px;"><img src="<?php echo $template;?>/imagenes/mod_formulario_top.jpg" alt="" border="0" /></div>
  <div style="float:left; width:30px; margin-top:8px;"><?php echo $datos->icono;?></div>
  <div style="float:left; width:164px; margin-left:10px; margin-top:8px;"><div class="tit"><?php echo $datos->titulo;?></div></div>
  <div style="float:left; width:188px; margin:0px 8px 8px;">
    <div class="txt"><?php echo $datos->descripcion;?></div>
    <div class="link" align="right"><a href="<?php echo $datos->link;?>" title="Entrar" class="boxs_link"><?php echo $params->get('vermas') ? '<img src="'.$template.'/imagenes/mod_autoconsulta_entrar.jpg" alt="Entrar" border="0" title="Entrar" />':'Entrar &raquo;';?></a></div>
  </div>
  <div style="float:left; width:204px;"><img src="<?php echo $template;?>/imagenes/mod_formulario_bottom.jpg" alt="" border="0" /></div>
</div>