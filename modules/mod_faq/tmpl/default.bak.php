<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
		$app					=& JFactory::getApplication();
		$template				= JURI::base() . 'templates/' . $app->getTemplate();
?>
<div class="mod_faq">
    <div align="left">
      <span class="titulo"><a href="<?php echo $datos->link;?>" title="<?php echo $datos->titulo;?>"><?php echo $datos->titulo;?></a></span>
      <span class="link"><a href="<?php echo $datos->linkforo;?>" title="Haz tu consulta aqu&iacute;">Haz tu consulta aqu&iacute; &nbsp;&nbsp;&raquo;</a></span>
    </div>
  <?php if ( count( $datos->items ) ) : $pc = round(100/count( $datos->items )); $at = 888 * count( $datos->items );?>
    <div class="huincha" align="left">
      <div style="float:left; width:28px; padding-top:3px; padding-bottom:3px;"><a href="javascript:void(0);" onclick="javascript:return false;" id="mod_faq_anterior" title="Retroceder a la pregunta anterior"><img src="<?php echo $template;?>/imagenes/faq_izquierda.jpg" alt="" border="0" /></a></div>
      <div class="items" id="mod_faq_lista">
      	<div style="width:<?php echo $at;?>px;">
        <?php foreach( $datos->items as $i => $item ): ?>
          <div class="item" align="center">
          <span style="text-align:left;">
            <span class="categoria"><?php echo $item->cattitle;?></span><br />
            <a href="<?php echo $item->link;?>" title="<?php echo $item->title;?>"><?php echo $item->title;?></a>
          </span>
          </div>
        <?php endforeach; ?>
        </div>
      <?php
	  /*
        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="faq">
         <tr>
         <?php foreach( $datos->items as $i => $item ): ?>
          <td valign="middle" width="<?php echo $pc;?>%" align="left"<?php echo !$i?' style="border:0px;"':'';?>><a href="<?php echo $item->link;?>" title="<?php echo $item->title;?>"><?php echo $item->title;?></a></td>
         <?php endforeach; ?>
         </tr>
        </table>
	  */
	  ?>
      </div>
      <div style="float:left; width:28px; padding-top:3px; padding-bottom:3px;" align="right"><a href="javascript:void(0);" onclick="javascript:return false;" id="mod_faq_siguiente" title="Avanzar a la siguiente pregunta"><img src="<?php echo $template;?>/imagenes/faq_derecha.jpg" alt="" border="0" /></a></div>
    </div>
  <?php endif; ?>
</div>
