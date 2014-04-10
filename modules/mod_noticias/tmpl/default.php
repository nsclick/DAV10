<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
		$app					=& JFactory::getApplication();
		$template				= JURI::base() . 'templates/' . $app->getTemplate();
?>
<div class="mod_boxshome mod_noticias" align="left">
	<h2><?php echo $datos->subtitulo;?></h2>
	<h1><?php echo $datos->titulo;?></h1>
    <div class="noticia">
      <table width="265" border="0" cellpadding="0" cellspacing="0" class="mod_noticias">
        <tr>
        <?php if( $datos->row->img ) : ?>
          <td align="left" valign="top" style="padding-right:10px; border-right:1px dotted #999;">
          	<a href="<?php echo $datos->row->link;?>" title="<?php echo $datos->row->title;?>"><?php echo $datos->row->img;?></a>
            <div class="titulo"><a href="<?php echo $datos->row->link;?>" title="<?php echo $datos->row->title;?>"><?php echo $datos->row->title;?></a></div>
            <div class="fecha">Fecha: <?php echo date("d/m/Y", strtotime($datos->row->publish_up));?></div>
          </td>
        <?php endif; ?>
          <td align="left" valign="top" style="padding-left:10px;">
          	<div class="descripcion"><?php echo $datos->row->cat_description;?></div>
            <div align="right"><a href="<?php echo $datos->catlink;?>" title="Ver m&aacute;s"><img src="<?php echo $template;?>/imagenes/btn_mod_noticias_vermas.jpg" alt="Ver m&aacute;s" title="Ver m&aacute;s" border="0" /></a></div>
          </td>
        </tr>
      </table>
    </div>
</div>