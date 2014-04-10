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
        <td id="quickfaq-cat<?php echo $categoria->id;?>-td" class="<?php echo $c ? 'pestana_off':'pestana_on';?>"><a href="javascript:void(0);" onclick="javascript:return false;" id="quickfaq-cat<?php echo $categoria->id;?>-link" title="<?php echo $categoria->cattitle;?>"><?php echo $categoria->cattitle;?></a></td>
      <?php endif; endforeach; ?>
      </tr>
    </table>
  </div>
<?php foreach( $datos->categorias as $c => $categoria ) : ?>
  <?php if( count( $categoria->articles ) ): ?>
  <div class="articulos" id="quickfaq-cat<?php echo $categoria->id;?>-arts" style="display:<?php echo $c ? 'none':'block';?>;">
  	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="articulos">
    <?php foreach( $categoria->articles as $a => $article ) : ?>
      <tr>
        <td align="left" valign="middle">
        	<h3><a href="<?php echo $article->link; ?>" title="<?php echo $article->title; ?>"><?php echo $article->title;?></a></h3>
            <div class="descripcion"><?php echo $article->introtext;?></div>
        </td>
      </tr>
    <?php endforeach;?>
    </table>
  </div>
  <?php endif; ?>
  <?php endforeach; ?>
</div>