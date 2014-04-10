<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
		$app					=& JFactory::getApplication();
		$template				= JURI::base() . 'templates/' . $app->getTemplate();
?>
<div class="mod_categorias">
  <div class="pestanas">
  	<table width="" border="0" cellpadding="0" cellspacing="0" class="pestanas">
      <tr>
      <?php foreach( $datos->categorias as $c => $categoria ) : if( count( $categoria->articles ) ): ?>
        <td id="mod_categorias-cat<?php echo $categoria->id;?>-td" class="<?php echo $c ? 'pestana_off':'pestana_on';?>"><!--<a href="javascript:void(0);" onclick="javascript:mod_categorias_pestana(); return false;" title="<?php echo $categoria->cattitle;?>">--><a href="javascript:void(0);" onclick="javascript:return false;" id="mod_categorias-cat<?php echo $categoria->id;?>-link" title="<?php echo $categoria->cattitle;?>"><?php echo $categoria->cattitle;?></a></td>
      <?php endif; endforeach; ?>
      </tr>
    </table>
  </div>
<?php foreach( $datos->categorias as $c => $categoria ) : ?>
  <?php if( count( $categoria->articles ) ): ?>
  <div class="articulos" id="mod_categorias-cat<?php echo $categoria->id;?>-arts" style="display:<?php echo $c ? 'none':'block';?>;">
  	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="articulos">
    <?php foreach( $categoria->articles as $a => $article ) : ?>
      <tr>
      <?php if( $article->img ) : ?>
      	<td width="100" align="center" valign="middle"><?php echo $article->img;?></td>
      <?php endif;?>
        <td width="20" align="center" valign="middle"><img src="<?php echo $template;?>/imagenes/mod_categorias_plus.jpg" alt="" /></td>
        <td align="left" valign="middle">
        	<h3><?php echo $article->title;?></h3>
            <div class="descripcion"><?php echo $article->introtext;?></div>
        </td>
      </tr>
    <?php endforeach;?>
    </table>
  </div>
  <?php endif; ?>
  <?php endforeach; ?>
</div>